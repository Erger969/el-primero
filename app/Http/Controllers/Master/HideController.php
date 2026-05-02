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
        
        // Verificar que el usuario es Master (2) o Admin (3)
        if (!in_array($user->role_id, [2, 3])) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'No tienes permiso para realizar esta acción.'], 403);
            }
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        
        // No puede ocultar sus propias publicaciones (solo Masters, Admins si pueden por gestión)
        if ($post->user_id == $user->id && $user->role_id == 2) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'No puedes ocultar tus propias publicaciones.'], 422);
            }
            return back()->with('error', 'No puedes ocultar tus propias publicaciones.');
        }
        
        if (!$post->is_hidden) {
            // Contar cuántas ocultaciones hizo hoy (solo para Masters)
            if ($user->role_id == 2) {
                $todayHides = MasterActivity::where('master_id', $user->id)
                    ->where('action', 'hide')
                    ->whereDate('created_at', today())
                    ->count();
                
                if ($todayHides >= 3) {
                    if (request()->wantsJson()) {
                        return response()->json(['error' => 'Has alcanzado el límite de 3 ocultaciones por día.'], 422);
                    }
                    return back()->with('error', 'Has alcanzado el límite de 3 ocultaciones por día.');
                }
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
            
            $message = 'Publicación ocultada exitosamente por 48 horas.';
            $status = 'hidden';
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
            
            $message = 'Publicación visible nuevamente.';
            $status = 'visible';
        }

        if (request()->wantsJson()) {
            $hidesToday = MasterActivity::where('master_id', $user->id)
                ->where('action', 'hide')
                ->whereDate('created_at', today())
                ->count();

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status,
                'hides_today' => $hidesToday,
                'remaining' => max(0, 3 - $hidesToday)
            ]);
        }
        
        return back()->with('success', $message);
    }
}