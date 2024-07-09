<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('posts', PostController::class)->only(['create', 'store']);
    Route::resource('posts.comments', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);

    Route::post('likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
});

Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('posts/{topic?}', [PostController::class, 'index'])->name('posts.index');
