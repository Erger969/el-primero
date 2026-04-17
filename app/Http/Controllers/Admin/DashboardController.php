<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Report;
use App\Models\MasterRequest;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== 8.2.1: Publicaciones por período ==========
        $postsToday = Post::whereDate('created_at', today())->count();
        $postsThisWeek = Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $postsThisMonth = Post::whereMonth('created_at', now()->month)->count();

        // ========== 8.2.2: Top 5 publicaciones con más comentarios ==========
        $topCommentedPosts = Post::with('user')
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->limit(5)
            ->get();

        // ========== 8.2.3: Top 5 publicaciones con más reacciones ==========
        $topReactedPosts = Post::with('user')
            ->withCount('reactions')
            ->orderBy('reactions_count', 'desc')
            ->limit(5)
            ->get();

        // ========== 8.2.4: Top 5 usuarios que más publican ==========
        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        // ========== 8.2.5: Usuarios por carrera ==========
        $usersByCareer = User::select('career_id', DB::raw('count(*) as total'))
            ->with('career')
            ->groupBy('career_id')
            ->get();

        // ========== 8.2.6: Contadores de reportes y solicitudes ==========
        $totalReports = Report::where('status', 'pending')->count();
        $totalMasterRequests = MasterRequest::where('status', 'pending')->count();

        // Estadísticas adicionales
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $totalComments = Comment::count();

        // Publicaciones recientes
        $recentPosts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'postsToday', 'postsThisWeek', 'postsThisMonth',
            'topCommentedPosts', 'topReactedPosts',
            'topUsers', 'usersByCareer', 'totalReports', 'totalMasterRequests',
            'totalUsers', 'totalPosts', 'totalComments', 'recentPosts'
        ));
    }
}