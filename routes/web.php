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

// Rutas de perfil y publicaciones (protegidas por auth logeados)
Route::middleware(['auth', 'not_suspended'])->group(function () {
    // Perfil público (ver cualquier usuario)
    Route::get('/profile/{id}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    // Edición de perfil (Breeze original)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    // Actualizar descripción (nuevo)
    Route::put('/profile/description', [App\Http\Controllers\ProfileController::class, 'updateDescription'])->name('profile.update-description');
    // Feed y publicaciones
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
    // Reportes
    Route::post('/posts/{post}/report', [App\Http\Controllers\ReportController::class, 'store'])->name('posts.report');
    // Seguimiento de usuarios para Master
    Route::post('/profile/request-master', [App\Http\Controllers\ProfileController::class, 'requestMaster'])->name('profile.request-master');
});

// Ruta de prueba de roles
Route::get('/admin-test', function () {
    return 'Eres administrador';
})->middleware(['auth', 'role:admin']);

// Ruta de Admin Dashboard
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Gestión del dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // Gestión de Carreras
    Route::resource('careers', App\Http\Controllers\Admin\CareerController::class);
    // Gestión de Usuarios
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show', 'create', 'store']);
    Route::put('/users/{id}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
    Route::put('/users/{id}/restore', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore');
    // Gestión de Publicaciones
    Route::resource('posts', App\Http\Controllers\Admin\PostController::class)->except(['show', 'create', 'store']);
    Route::put('/posts/{id}/hide', [App\Http\Controllers\Admin\PostController::class, 'hide'])->name('posts.hide');
    Route::put('/posts/{id}/show', [App\Http\Controllers\Admin\PostController::class, 'show'])->name('posts.show');
    // Gestión de Reportes
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::put('/reports/{id}/resolve', [App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');
    Route::put('/reports/{id}/reject', [App\Http\Controllers\Admin\ReportController::class, 'reject'])->name('reports.reject');
    Route::put('/reports/{id}/hide-post', [App\Http\Controllers\Admin\ReportController::class, 'hidePost'])->name('reports.hide-post');
    // vista de solicitudes de ascenso a Master
    Route::get('/master-requests', [App\Http\Controllers\Admin\MasterRequestController::class, 'index'])->name('master-requests.index');
    Route::put('/master-requests/{id}/approve', [App\Http\Controllers\Admin\MasterRequestController::class, 'approve'])->name('master-requests.approve');
    Route::put('/master-requests/{id}/reject', [App\Http\Controllers\Admin\MasterRequestController::class, 'reject'])->name('master-requests.reject');
    // Auditoría de acciones de Masters
    Route::get('/master-activity', [App\Http\Controllers\Admin\MasterActivityController::class, 'index'])->name('master-activity.index');
    Route::delete('/master-activity/{id}/revoke', [App\Http\Controllers\Admin\MasterActivityController::class, 'revokePermissions'])->name('master-activity.revoke');
});

// Rutas para Masters (ocultar publicaciones)
Route::middleware(['auth'])->prefix('master')->name('master.')->group(function () {
    Route::post('/posts/{post}/hide', [App\Http\Controllers\Master\HideController::class, 'toggle'])->name('posts.hide');
});

require __DIR__.'/auth.php';