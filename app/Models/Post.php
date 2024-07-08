<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMakrdownToHtml;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use ConvertsMakrdownToHtml;
    use HasFactory;

    protected $fillable = ['title', 'body', 'html', 'user_id'];

    /**
     * Get the user that owns the Post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get all of the comments for the Post
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function title(): Attribute
    {
        return Attribute::set(static fn ($value) => Str::title($value));
    }

    public function showRoute(array $parameters = []): string
    {
        return route('posts.show', [$this->id, Str::slug($this->title), ...$parameters]);
    }
}
