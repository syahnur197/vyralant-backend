<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class PostService
{
    public function getPostQuery(): Builder
    {
        return Post::with([
            'poster',
            'media',
            'rating',
        ])
            ->select('posts.*', 'ratings.ratings', 'ratings.upvotes', 'ratings.downvotes')
            ->join('ratings', 'ratings.rateable_id', '=', 'posts.id')
            ->withCount('comments')
            ->orderBy('ratings.decayed_ratings', 'desc')
            ->orderBy('posts.created_at', 'desc');
    }
}
