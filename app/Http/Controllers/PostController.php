<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    // public function create()
    // {
    //     return view('posts.create');
    // }

    // // Guardar nueva publicación
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:200',
    //         'content' => 'required|string',
    //         'images' => 'nullable|array|max:5',
    //         'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     // Aquí luego agregaremos subida a Cloudinary
    //     $post = Post::create([
    //         'user_id' => Auth::id(),
    //         'title' => $request->title,
    //         'content' => $request->content,
    //         'images' => null, // Temporal, luego Cloudinary
    //         'is_hidden' => false,
    //     ]);

    //     return redirect()->route('feed')->with('success', 'Publicación creada exitosamente.');
    // }

    // // Mostrar detalle de una publicación
    // public function show(Post $post)
    // {
    //     $post->load(['user', 'comments.user', 'reactions']);
    //     $userReaction = Reaction::where('user_id', Auth::id())
    //         ->where('post_id', $post->id)
    //         ->first();
        
    //     return view('posts.show', compact('post', 'userReaction'));
    // }
}