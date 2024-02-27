<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');

});

Route::controller(PostController::class)->group(function () {
    Route::get('posts', 'index')->name('post.index');
    Route::get('posts/{post}', 'show')->name('post.show');
});
