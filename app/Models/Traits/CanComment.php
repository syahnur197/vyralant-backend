<?php

namespace App\Models\Traits;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanComment
{
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'posted_by');
    }

    public function comment(Post $post, string $content)
    {
        return $this->comments()->create([
            'post_id' => $post->id,
            'content' => $content,
        ]);
    }
}
