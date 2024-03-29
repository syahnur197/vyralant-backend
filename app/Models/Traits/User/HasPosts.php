<?php

namespace App\Models\Traits\User;

use App\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasPosts
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'posted_by');
    }
}
