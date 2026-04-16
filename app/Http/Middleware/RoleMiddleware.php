<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $roles = [
            'university' => 1,
            'master' => 2,
            'admin' => 3,
        ];
        
        $requiredRoleId = $roles[$role] ?? null;
        
        if (!$requiredRoleId || auth()->user()->role_id !== $requiredRoleId) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        
        return $next($request);
    }
}