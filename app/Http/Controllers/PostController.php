<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;
//use Cloudinary\Cloud;
//use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;    //API para optimizacion de imagenes y videos

class PostController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // Feed principal (para usuarios logueados)
    public function feed()
    {
        // Obtener publicaciones no ocultas, con relaciones, ordenadas por fecha
        $posts = Post::with(['user', 'comments.user', 'reactions'])
            ->visible()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Obtener las reacciones del usuario actual para cada publicación
        $userReactions = Reaction::where('user_id', Auth::id())
            ->whereIn('post_id', $posts->pluck('id'))
            ->get()
            ->keyBy('post_id');
        
        // Verificar que la vista existe
        if (!view()->exists('feed')) {
            dd('La vista feed no existe en resources/views/feed.blade.php');
        }
        
        return view('feed', compact('posts', 'userReactions'));
    }

    // Mostrar formulario para crear publicación
        public function create()
        {
            return view('posts.create');
        }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageUrls = [];

        if ($request->hasFile('images')) {
            // Configuración manual de Cloudinary (directa, sin facade)
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => 'dmbriummc',
                    'api_key'    => '932545162843198',
                    'api_secret' => 'RIGYbTS-2FHOblLsuWGhAkj7X78',
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            foreach ($request->file('images') as $image) {
                $uploadResult = $cloudinary->uploadApi()->upload($image->getRealPath(), [
                    'folder' => 'red_social_publicaciones',
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'limit'
                    ]
                ]);
                $imageUrls[] = $uploadResult['secure_url'];
            }
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'images' => json_encode($imageUrls),
            'is_hidden' => false,
        ]);

        return redirect()->route('feed')->with('success', 'Publicación creada exitosamente.');
    }

    // Mostrar detalle de una publicación
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'reactions']);
        $userReaction = Reaction::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();
        
        return view('posts.show', compact('post', 'userReaction'));
    }

    // Editar publicación
    public function edit(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    // Actualizar publicación
    public function update(Request $request, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('feed')->with('success', 'Publicación actualizada.');
    }

    // Eliminar publicación
    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role_id !== 3) {
            abort(403);
        }

        $post->delete();
        return redirect()->route('feed')->with('success', 'Publicación eliminada.');
    }
}