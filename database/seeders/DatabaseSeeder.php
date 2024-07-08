<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TopicSeeder::class);
        $topics = Topic::all();

        $users = User::factory(10)->create();

        $posts = Post::factory(200)
            ->withFixtures()
            ->has(Comment::factory(15)->recycle($users))
            ->recycle($users, $topics)
            ->create();

        $comments = Comment::factory(100)->recycle($users)->recycle($posts)->create();

        $joe = User::factory()
            ->has(Post::factory(50)->recycle($topics)->withFixtures())
            ->has(Comment::factory(120))->recycle($posts)
            ->has(Like::factory()->forEachSequence(
                ...$posts->random(100)->map(
                    fn (Post $post) => ['likeable_id' => $post])
            ))
            ->create([
                'name' => env('TEST_NAME'),
                'email' => env('TEST_EMAIL'),
                'password' => env('TEST_PASSWORD'),
            ]);
    }
}
