<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('Delete the comment', function () {
    delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can Delete a commnet', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comments.destroy', $comment));

    $this->assertModelMissing($comment);
});

it('redirects to the post show page', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comments.destroy', $comment))
        ->assertRedirect(route('posts.show', $comment->post_id));
});

it('prevents deleted a comment that\'s not yours', function () {

    actingAs(User::factory()->create())
        ->delete(route('comments.destroy', Comment::factory()->create()))
        ->assertForbidden();
});

it('cant delete a comment if its older than 1 hour', function () {

    $comment = Comment::factory()->create([
        'created_at' => now()->subHours(2),
    ]);

    actingAs($comment->user)
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();
});

it('redirects to the post show page with page query param', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comments.destroy', ['comment' => $comment, 'page' => 2]))
        ->assertRedirect(route('posts.show', ['post' => $comment->post_id, 'page' => 2]));
});
