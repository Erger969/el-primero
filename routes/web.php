<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Feed y publicaciones
    // Route::get('/feed', [App\Http\Controllers\PostController::class, 'feed'])->name('feed');
    Route::resource('posts', App\Http\Controllers\PostController::class)->except(['index']);
    Route::get('/feed', [App\Http\Controllers\PostController::class, 'feed'])->name('feed');
    Route::get('/posts/create', [App\Http\Controllers\PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [App\Http\Controllers\PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [App\Http\Controllers\PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [App\Http\Controllers\PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');
    // Comentarios (agregar esta línea)
    Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    // Reacciones
    Route::post('/posts/{post}/react', [App\Http\Controllers\ReactionController::class, 'toggle'])->name('posts.react');
});

// Ruta de prueba para middleware de roles
Route::get('/admin-test', function () {
    return 'Eres administrador';
})->middleware(['auth', 'role:admin']);

// Rutas para administrador (protegidas)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';