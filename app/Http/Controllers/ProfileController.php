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
            'descripcion' => 'nullable|string|max:500',
        ]);
        
        $user->update([
            'descripcion' => $request->descripcion,
        ]);
        
        return redirect()->route('profile.show', $user->id)->with('success', 'Perfil actualizado.');
    }
}