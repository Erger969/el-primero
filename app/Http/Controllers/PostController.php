<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\Career;
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
    public function feed(Request $request)
    {
        $user = Auth::user();
        $query = Post::with(['user', 'comments.user', 'reactions']);
        
        // Solo usuarios normales ven solo lo visible. Masters (2) y Admins (3) ven todo.
        if ($user->role_id < 2) {
            $query->visible();
        }
        
        // Filtro por carrera
        if ($request->filled('career_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('career_id', $request->career_id);
            });
        }
        
        // Filtro por fecha
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
            }
        }
        
        // Ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'most_commented':
                    $query->withCount('comments')->orderBy('comments_count', 'desc');
                    break;
                case 'most_reactions':
                    $query->withCount('reactions')->orderBy('reactions_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Lógica de búsqueda categorizada
        $search = $request->input('search');
        $searchType = $request->input('search_type', 'title');
        $searchCounts = ['title' => 0, 'content' => 0, 'user' => 0];

        if ($search) {
            $visibilityQuery = Post::query();
            if ($user->role_id < 2) {
                $visibilityQuery->visible();
            }

            // Calcular conteos para cada categoría
            $searchCounts['title'] = (clone $visibilityQuery)->where('title', 'like', "%{$search}%")->count();
            $searchCounts['content'] = (clone $visibilityQuery)->where('content', 'like', "%{$search}%")->count();
            $searchCounts['user'] = (clone $visibilityQuery)->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('lastname', 'like', "%{$search}%");
            })->count();

            // Filtrar según el tipo seleccionado
            if ($searchType === 'content') {
                $query->where('content', 'like', "%{$search}%");
            } elseif ($searchType === 'user') {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")->orWhere('lastname', 'like', "%{$search}%");
                });
            } else {
                $query->where('title', 'like', "%{$search}%");
            }
        }
        
        $posts = $query->paginate(10);
        
        $userReactions = Reaction::where('user_id', Auth::id())
            ->whereIn('post_id', $posts->pluck('id'))
            ->get()
            ->keyBy('post_id');
        
        // Obtener carreras para el filtro
        $careers = Career::orderBy('nombre')->get();
        
        return view('feed', compact('posts', 'userReactions', 'careers', 'searchCounts', 'search', 'searchType'));
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
            abort(403, 'No tienes permiso para editar esta publicación.');
        }

        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        // Guardar historial de cambios (auditoría)
        if ($post->title !== $request->title) {
            \App\Models\PostModification::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'campo_modificado' => 'titulo',
                'valor_anterior' => $post->title,
                'valor_nuevo' => $request->title,
            ]);
        }

        if ($post->content !== $request->content) {
            \App\Models\PostModification::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
                'campo_modificado' => 'contenido',
                'valor_anterior' => $post->content,
                'valor_nuevo' => $request->content,
            ]);
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Publicación actualizada.');
    }

    // Eliminar publicación
    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && auth()->user()->role_id !== 3) {
            abort(403, 'No tienes permiso para eliminar esta publicación.');
        }

        $post->delete();
        return redirect()->route('feed')->with('success', 'Publicación eliminada.');
    }
}