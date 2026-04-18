<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'category' => 'required|in:spam,acoso,ofensivo,desinformacion,otro',
            'reason' => 'nullable|string|max:500',
        ]);

        // Verificar si el usuario ya reportó esta publicación
        $existingReport = Report::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();

        if ($existingReport) {
            return back()->with('error', 'Ya has reportado esta publicación anteriormente.');
        }

        // Crear el reporte
        $report = Report::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'category' => $request->category,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Verificar si la publicación alcanzó 25 reportes
        $reportCount = Report::where('post_id', $post->id)->count();

        if ($reportCount >= 25) {
            $post->is_hidden = true;
            $post->hidden_until = now()->addDays(2);
            $post->save();
        }

        return back()->with('success', 'Reporte enviado. Gracias por ayudarnos a mantener la comunidad segura.');
    }
}