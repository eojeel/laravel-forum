<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMakrdownToHtml;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use ConvertsMakrdownToHtml;
    use HasFactory;

    protected $fillable = ['body', 'html'];

    /**
     * Get the user that owns the Comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Post that owns the Comment
     */
    public function Post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
