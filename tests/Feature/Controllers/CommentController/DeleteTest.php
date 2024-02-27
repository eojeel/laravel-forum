<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('Delete the comment', function () {
    delete(route('comment.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});

it('can Delete a commnet', function () {
    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comment.destroy', $comment));

    $this->assertModelMissing($comment);
});

it('redirects to the post show page', function () {

    $comment = Comment::factory()->create();

    actingAs($comment->user)->delete(route('comment.destroy', $comment))
        ->assertRedirect(route('post.show', $comment->post_id));
});

it('prevents deleted a comment thats not yours', function () {

    $comment = Comment::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route('comment.destroy', Comment::factory()->create()))
        ->assertForbidden();
});
