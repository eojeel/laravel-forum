<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\put;

it('requires authentication', function () {

    put(route('comments.update', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('Can update a comment', function () {

    $comment = Comment::factory()->create(['body' => 'old body']);
    $newBody = 'new body';

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => $newBody]);

    $this->assertDatabaseHas(Comment::class, [
        'id' => $comment->id,
        'body' => $newBody,
    ]);

});

it('redirects to the post show page', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', $comment), ['body' => 'new Body'])
        ->assertRedirect(route('posts.show', $comment->post));

});

it('redirects to the correct page of comments', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment, 'page' => 2]), ['body' => 'new Body'])
        ->assertRedirect(route('posts.show', ['post' => $comment->post, 'page' => 2]));
});

it('Cannot update a comment rom another users', function () {

    actingAs(User::factory()->create())
        ->put(route('comments.update', Comment::factory()->create()))
        ->assertForbidden();
});

it('Requires a valid body', function ($body) {

    $comment = Comment::factory()->create();

    actingAs($comment->user)
        ->put(route('comments.update', ['comment' => $comment]), [
            'body' => $body,
        ])->assertInvalid('body');
})
    ->with(['', ' ', null, 1, 1.2, str_repeat('a', 2501)]);
