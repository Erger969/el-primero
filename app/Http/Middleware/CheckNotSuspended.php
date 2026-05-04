<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckNotSuspended
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->role_id == 4 && $user->role_id != 3) { // Solo si es suspendido y no es Admin
                // Verificar si la suspensión ha expirado
                if ($user->suspended_until && $user->suspended_until < now()) {
                    // Restaurar usuario automáticamente
                    $user->role_id = 1;
                    $user->suspended_until = null;
                    $user->save();
                    
                    return $next($request);
                }

                // Si es una petición de escritura (POST, PUT, PATCH, DELETE), bloquear
                if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                    if ($request->wantsJson()) {
                        return response()->json(['error' => 'Tu cuenta está suspendida. Solo puedes ver contenido.'], 403);
                    }
                    
                    $msg = 'Tu cuenta está suspendida';
                    if ($user->suspended_until) {
                        $msg .= ' hasta el ' . $user->suspended_until->format('d/m/Y H:i');
                    }
                    $msg .= '. Solo puedes navegar en modo lectura.';
                    
                    return back()->with('error', $msg);
                }
            }
        }

        return $next($request);
    }
}
