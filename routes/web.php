<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Recuperación de contraseña personalizada
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [App\Http\Controllers\Auth\CustomForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\CustomForgotPasswordController::class, 'sendResetCode'])->name('password.email');
    Route::get('/reset-password', [App\Http\Controllers\Auth\CustomForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [App\Http\Controllers\Auth\CustomForgotPasswordController::class, 'reset'])->name('password.update');
});

require __DIR__.'/auth.php';
