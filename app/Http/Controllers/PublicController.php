<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Reaction;

class PublicController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
            ->visible()
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        
        // Usar caché para tendencias (se actualiza cada hora)
        $trendingPosts = Cache::remember('trending_posts', 3600, function () {
            return Post::with('user')
                ->visible()
                ->withCount(['comments', 'reactions'])
                ->where('created_at', '>=', now()->subWeek())
                ->orderByRaw('(comments_count + reactions_count) DESC')
                ->limit(6)
                ->get();
        });
        
        // Obtener reacciones del usuario actual (si está logueado)
        $userReactions = [];
        if (auth()->check()) {
            $userReactions = Reaction::where('user_id', auth()->id())
                ->whereIn('post_id', $posts->pluck('id'))
                ->get()
                ->keyBy('post_id');
        }
        
        return view('home', compact('posts', 'trendingPosts', 'userReactions'));
    }
}
