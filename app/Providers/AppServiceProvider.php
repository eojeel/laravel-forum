<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // remove the data array wrap in the resource.
        JsonResource::withoutWrapping();

        Model::preventLazyLoading(! app()->isProduction());

        Relation::enforceMorphMap([
            'post' => Post::class,
            'comment' => Comment::class,
            'user' => User::class,
        ]);
    }
}
