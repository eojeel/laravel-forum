<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('posts', PostController::class)->only(['create', 'store']);
    Route::resource('posts.comments', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);

    Route::post('likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('likes/{type}/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');
});

Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('posts/{topic?}', [PostController::class, 'index'])->name('posts.index');
Route::get('/', [PostController::class, 'index'])->name('index');
