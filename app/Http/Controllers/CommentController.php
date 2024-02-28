<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $attributes = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        $comment = Comment::make($attributes)
            ->user()->associate($request->user())
            ->post()->associate($post)
            ->save();

        return to_route('post.show', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $attributes = $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        $comment->update($attributes);

        return to_route('post.show', ['post' => $comment->post_id, 'page' => $request->query('page')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        $comment->delete();

        return to_route('post.show', ['post' => $comment->post_id, 'page' => $request->query('page')]);
    }
}
