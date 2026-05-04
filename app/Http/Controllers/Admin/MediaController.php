<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        // Obtenemos posts que tengan imágenes (campo images no sea null o [])
        $postsWithImages = Post::with('user')
            ->whereNotNull('images')
            ->where('images', '!=', '[]')
            ->latest()
            ->paginate(12);

        return view('admin.media.index', compact('postsWithImages'));
    }
}
