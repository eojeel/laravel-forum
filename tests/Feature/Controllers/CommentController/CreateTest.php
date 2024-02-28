<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('Can store a coment', function () {

    $user = User::factory()->create();
    $post = Post::factory()->create();

    $comment = 'This is a comment';

    actingAs($user)->post(route('posts.comment.store', $post->id), [
        'body' => $comment,
    ]);

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => $comment,
    ]);

});

it('redirects to the post show page', function () {

    $post = Post::factory()->create();

    actingAs(User::factory()->create())->post(route('posts.comment.store', $post), [
        'body' => 'This is a comment',
    ])->assertRedirect(route('post.show', $post));
});

it('requires a valid body', function () {

    $post = Post::factory()->create();

    actingAs(User::factory()->create())->post(route('posts.comment.store', $post), [
        'body' => null,
    ])->assertInvalid('body');
})
    ->with(['', ' ', null, 1, 1.2, str_repeat('a', 2501)]);

it('requires authentication', function () {

    post(route('posts.comment.store', Post::factory()->create()), [
        'body' => 'This is a comment',
    ])->assertRedirect(route('login'));

});
