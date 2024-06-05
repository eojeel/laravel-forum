<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
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
            ->create([
                'name' => 'joe',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
    }
}
