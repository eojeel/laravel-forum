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
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('posts.comment', CommentController::class)->shallow();
    //Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    //Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
    //Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comment.update');

});

Route::controller(PostController::class)->group(function () {
    Route::get('posts', 'index')->name('post.index');
    Route::get('posts/{post}', 'show')->name('post.show');
});
