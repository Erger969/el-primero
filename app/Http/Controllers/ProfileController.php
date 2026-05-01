<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form (edición propia).
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // ========== NUEVOS MÉTODOS AGREGADOS ==========

    /**
     * Ver perfil público de cualquier usuario (propio o ajeno)
     */
    public function show($id)
    {
        $user = User::with('career')->findOrFail($id);
        $posts = Post::where('user_id', $user->id)
            ->visible()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $isOwnProfile = (Auth::id() === $user->id);
        
        return view('profile.show', compact('user', 'posts', 'isOwnProfile'));
    }

    /**
     * Actualizar descripción del perfil (método adicional)
     */
    public function updateDescription(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'career_id' => 'required|exists:careers,id',
            'descripcion' => 'nullable|string|max:500',
        ]);
        
        $user->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'career_id' => $request->career_id,
            'descripcion' => $request->descripcion,
        ]);
        
        return redirect()->route('profile.show', $user->id)->with('success', 'Perfil actualizado exitosamente.');
    }

    // Solicitar ascenso a Master
    public function requestMaster()
    {
        $user = Auth::user();
        
        // Verificar que el usuario es universitario (role_id = 1)
        if ($user->role_id != 1) {
            return back()->with('error', 'Solo los usuarios universitarios pueden solicitar ser Master.');
        }
        
        // Verificar si ya tiene una solicitud pendiente
        $existingRequest = \App\Models\MasterRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        
        if ($existingRequest) {
            return back()->with('error', 'Ya tienes una solicitud pendiente de aprobación.');
        }
        
        // Crear la solicitud
        \App\Models\MasterRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Solicitud enviada. Espera la aprobación del administrador.');
    }
}