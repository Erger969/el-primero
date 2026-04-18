<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Post;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'post.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function resolve($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'resolved';
        $report->save();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Reporte marcado como resuelto.');
    }

    public function reject($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'rejected';
        $report->save();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Reporte rechazado.');
    }

    public function hidePost($id)
    {
        $report = Report::findOrFail($id);
        $post = $report->post;
        
        $post->is_hidden = true;
        $post->hidden_until = now()->addDays(2);
        $post->save();

        $report->status = 'resolved';
        $report->save();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Publicación ocultada exitosamente.');
    }
}