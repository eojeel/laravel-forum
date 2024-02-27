<?php

use App\Http\Resources\PostResource;
use App\Models\Post;

use function Pest\Laravel\{get};

it('should return the correct component', function () {
    get(route('post.index'))
        ->assertComponent('Posts/Index');
});

it('Passes posts to the view', function () {

    $post = Post::factory(3)->create();

    $post->load('user');

    get(route('post.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($post->reverse()));
});
