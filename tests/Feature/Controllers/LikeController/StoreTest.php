<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires authentication', function () {

    post(route('likes.store', ['Post', 1]))->assertRedirect(route('login'));
});

it('allows liking a likeable', function (Model $likeable) {

    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('index')
        ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
        ->assertRedirect(route('index'));

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);
    expect($likeable->refresh()->likes_count)->toBe(1);
})->with([
    fn () => Post::factory()->create(),
    fn () => Comment::factory()->create(),
]);

it('prevents liking something you already liked', function () {
    $like = Like::factory()->create();
    $likeable = $like->likeable;

    actingAs($like->user)
        ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
        ->assertForbidden();

});

it('can only likes supported models', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('index')
        ->post(route('likes.store', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is not supported', function () {
    actingAs(User::factory()->create())
        ->post(route('likes.store', ['foo', 1]))
        ->assertNotFound();
});
