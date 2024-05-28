<?php

use App\Models\Post;

it('uses title case for titles', function () {

    $post = Post::factory()->create(['title' => 'this is a title']);

    expect($post->title)->toBe('This Is A Title');

});

it('Can generate a route to the show page', function() {

    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route('posts.show', [$post, Str::slug($post->title)]));
});

it('can generate additional params', function() {

    $post = Post::factory()->create();

    expect($post->showRoute(['page' => 2]))
        ->toBe(route('posts.show', [$post, Str::slug($post->title), 'page' => 2]));
});

it('generates the html', function () {
    $post = Post::factory()->make(['body' => '## Hello world']);

    $post->save();

    expect($post->html)->toEqual(str($post->body)->markdown());
});
