<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Obtener publicaciones no ocultas, con el autor y ordenadas por fecha descendente
        $posts = Post::with('user')
            ->visible()
            ->orderBy('created_at', 'desc')
            ->paginate(6);  // 6 publicaciones por página (para grid 3x2)
        
        // Top 5 tendencias (las que tienen más comentarios + reacciones de la última semana)
        $trendingPosts = Post::with('user')
            ->visible()
            ->withCount(['comments', 'reactions'])
            ->where('created_at', '>=', now()->subWeek())
            ->orderByRaw('(comments_count + reactions_count) DESC')
            ->limit(5)
            ->get();
        
        return view('home', compact('posts', 'trendingPosts'));
    }
}
