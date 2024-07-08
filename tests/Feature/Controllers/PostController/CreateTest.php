<?php

use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->validData = fn () => [
        'title' => Str::title(fake()->sentence(10)),
        'topic_id' => Topic::factory()->create()->getKey(),
        'body' => fake()->sentence(110),
    ];
});

it('it requires authentication', function () {
    post(route('posts.store'))->assertRedirect(route('login'));
});

it('returns the correct component', function () {

    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});

it('passes the correct component', function () {
    $topics = Topic::factory(2)->create();

    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertHasResource('topics', TopicResource::collection($topics));
});

it('stores a post', function () {
    $user = User::factory()->create();

    $data = value($this->validData);

    actingAs($user)->post(route('posts.store'), $data);

    $this->assertDatabaseHas(Post::class, [
        ...$data,
        'user_id' => $user->id,
    ]);
});

it('redirects to the post show page', function () {

    $this->withoutExceptionHandling();
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), value($this->validData))
        ->assertRedirect(Post::latest('id')->first()->showRoute());
});

it('requires a valid data', function (array $badData, array|string $errors) {

    $user = User::factory()->create();

    actingAs($user)
        ->post(route('posts.store'), [...value($this->validData), ...$badData])
        ->assertInvalid($errors);
})->with([
    [['title' => null], 'title'],
    [['title' => true], 'title'],
    [['title' => 1], 'title'],
    [['title' => 1.5], 'title'],
    [['title' => str_repeat('a', 121)], 'title'],
    [['title' => str_repeat('a', 9)], 'title'],
    [['topic_id' => null], 'topic_id'],
    [['topic_id' => -1], 'topic_id'],
    [['body' => null], 'body'],
    [['body' => true], 'body'],
    [['body' => 1], 'body'],
    [['body' => 1.5], 'body'],
    [['body' => str_repeat('a', 10_001)], 'body'],
    [['body' => str_repeat('a', 99)], 'body'],
]);
