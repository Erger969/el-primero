<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Reaction;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'comments.user'])
            ->visible();

        $search = $request->input('search');
        $searchType = $request->input('search_type', 'title');
        $searchCounts = ['title' => 0, 'content' => 0, 'user' => 0];

        if ($search) {
            // Calcular conteos para cada categoría
            $searchCounts['title'] = Post::visible()->where('title', 'like', "%{$search}%")->count();
            $searchCounts['content'] = Post::visible()->where('content', 'like', "%{$search}%")->count();
            $searchCounts['user'] = Post::visible()->whereHas('user', function($q) use ($search) {
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

        $posts = $query->orderBy('created_at', 'desc')->paginate(6);
        
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
        
        return view('home', compact('posts', 'trendingPosts', 'userReactions', 'searchCounts', 'search', 'searchType'));
    }
}
