<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required|in:ya,ahh,ehh,ohh,uhh',
        ]);

        $reaction = Reaction::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();

        if ($reaction) {
            if ($reaction->type === $request->type) {
                $reaction->delete();
            } else {
                $reaction->update(['type' => $request->type]);
            }
        } else {
            Reaction::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'type' => $request->type,
            ]);
        }

        $counts = [
            'ya' => $post->reactions()->where('type', 'ya')->count(),
            'ahh' => $post->reactions()->where('type', 'ahh')->count(),
            'ehh' => $post->reactions()->where('type', 'ehh')->count(),
            'ohh' => $post->reactions()->where('type', 'ohh')->count(),
            'uhh' => $post->reactions()->where('type', 'uhh')->count(),
        ];

        return response()->json(['counts' => $counts]);
    }
}