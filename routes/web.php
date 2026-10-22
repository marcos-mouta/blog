<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/posts');

// Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rotas que exigem login (declaradas antes de posts/{post} para que "create" não seja confundido com um id)
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::resource('comments', CommentController::class)->only(['edit', 'update', 'destroy']);
});

// Rotas públicas
Route::resource('posts', PostController::class)->only(['index', 'show']);
