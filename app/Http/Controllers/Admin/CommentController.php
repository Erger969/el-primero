<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'post']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%");
                  });
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        AdminLog::log('comment_deleted', $comment, [
            'content_preview' => substr($comment->content, 0, 50),
            'author' => $comment->user->name
        ]);

        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comentario eliminado permanentemente.');
    }
}
