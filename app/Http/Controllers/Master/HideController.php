<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\MasterActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HideController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function toggle(Post $post)
    {
        $user = Auth::user();
        
        // Verificar que el usuario es Master (role_id = 2)
        if ($user->role_id != 2) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        
        // No puede ocultar sus propias publicaciones
        if ($post->user_id == $user->id) {
            return back()->with('error', 'No puedes ocultar tus propias publicaciones.');
        }
        
        if (!$post->is_hidden) {
            // Contar cuántas ocultaciones hizo hoy
            $todayHides = MasterActivity::where('master_id', $user->id)
                ->where('action', 'hide')
                ->whereDate('created_at', today())
                ->count();
            
            if ($todayHides >= 3) {
                return back()->with('error', 'Has alcanzado el límite de 3 ocultaciones por día.');
            }
            
            // Ocultar publicación
            $post->is_hidden = true;
            $post->hidden_until = now()->addDays(2);
            $post->save();
            
            // Registrar actividad
            MasterActivity::create([
                'master_id' => $user->id,
                'post_id' => $post->id,
                'action' => 'hide',
            ]);
            
            return back()->with('success', 'Publicación ocultada exitosamente.');
            
        } else {
            // Mostrar publicación
            $post->is_hidden = false;
            $post->hidden_until = null;
            $post->save();
            
            // Registrar actividad
            MasterActivity::create([
                'master_id' => $user->id,
                'post_id' => $post->id,
                'action' => 'show',
            ]);
            
            return back()->with('success', 'Publicación visible nuevamente.');
        }
    }
}