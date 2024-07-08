<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;

use function Pest\Laravel\{get};

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertComponent('Posts/Index');
});

it('Passes posts to the view', function () {

    $post = Post::factory(3)->create();

    $post->load(['user', 'topic']);

    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($post->reverse()));
});

it('topic filter', function () {

    $topic = Topic::factory()->create();

    $posts = Post::factory(2)->for($topic)->create();

    $outerPosts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index', ['topic' => $topic]))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('it passes topic to the view', function () {

    $topic = Topic::factory()->create();

    get(route('posts.index', ['topic' => $topic]))
        ->assertHasResource('selectedTopic', TopicResource::make($topic));
});
