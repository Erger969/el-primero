<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar usuarios
    public function index(Request $request)
    {
        $query = User::with('career');

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Filtro por carrera
        if ($request->filled('career')) {
            $query->where('career_id', $request->career);
        }

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $careers = Career::all();
        $roles = [1 => 'Universitario', 2 => 'Master', 3 => 'Administrador', 4 => 'Suspendido'];

        return view('admin.users.index', compact('users', 'careers', 'roles'));
    }

    // Mostrar formulario para editar usuario
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $careers = Career::all();
        $roles = [1 => 'Universitario', 2 => 'Master', 3 => 'Administrador'];
        
        return view('admin.users.edit', compact('user', 'careers', 'roles'));
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'career_id' => 'nullable|exists:careers,id',
            'role_id' => 'required|in:1,2,3',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->career_id = $request->career_id;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    // Suspender usuario (cambiar rol a 4)
    public function suspend(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role_id == 3) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se puede suspender a un administrador.');
        }

        $duration = $request->input('duration', 'permanent');
        $suspendedUntil = null;

        if ($duration !== 'permanent') {
            $suspendedUntil = now()->addDays((int)$duration);
        }

        $user->role_id = 4;
        $user->suspended_until = $suspendedUntil;
        $user->save();

        $message = 'Usuario suspendido exitosamente';
        $message .= $suspendedUntil ? ' hasta el ' . $suspendedUntil->format('d/m/Y H:i') : ' permanentemente';
        $message .= '.';

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    // Restaurar usuario (cambiar rol a 1)
    public function restore($id)
    {
        $user = User::findOrFail($id);
        $user->role_id = 1;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario restaurado exitosamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role_id == 3) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No se puede eliminar a un administrador.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}