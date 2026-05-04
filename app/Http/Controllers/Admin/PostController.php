<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Listar publicaciones
    public function index(Request $request)
    {
        $query = Post::with('user');

        // Filtro por estado (oculta/no oculta)
        if ($request->filled('hidden')) {
            if ($request->hidden == 'yes') {
                $query->where('is_hidden', true);
            } elseif ($request->hidden == 'no') {
                $query->where('is_hidden', false);
            }
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Búsqueda por título
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'users'));
    }

    // Mostrar formulario para editar publicación
    public function edit($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    // Actualizar publicación
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
        ]);

        $currentImages = is_array($post->images) ? $post->images : json_decode($post->images, true);
        if ($request->filled('removed_images')) {
            $removed = $request->removed_images;
            $currentImages = array_values(array_filter($currentImages, function($img) use ($removed) {
                return !in_array($img, $removed);
            }));
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'images' => $currentImages,
            'is_hidden' => $request->has('is_hidden'),
        ]);

        AdminLog::log('post_updated', $post);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Publicación actualizada exitosamente.');
    }

    // Ocultar publicación
    public function hide($id)
    {
        $post = Post::findOrFail($id);
        $post->is_hidden = true;
        $post->hidden_until = now()->addDays(2);
        $post->save();

        AdminLog::log('post_hidden', $post, ['title' => $post->title]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Publicación ocultada exitosamente.');
    }

    // Mostrar publicación (quitar ocultamiento)
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $post->is_hidden = false;
        $post->hidden_until = null;
        $post->save();

        AdminLog::log('post_restored', $post);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Publicación visible nuevamente.');
    }

    // Eliminar publicación
    public function destroy($id)
    {
        AdminLog::log('post_deleted', null, ['title' => $post->title, 'id' => $post->id]);
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Publicación eliminada exitosamente.');
    }
}