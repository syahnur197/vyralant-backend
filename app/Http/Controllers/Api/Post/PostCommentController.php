<?php

namespace App\Http\Controllers\Api\Post;

use App\Models\Post;

class PostCommentController
{
    public function index($slug)
    {
        $post = Post::with('comments.comments.comments.comments.comments')
            ->whereSlug($slug)
            ->first();

        $comments = $post->comments;

        return [
            'comments' => $comments,
        ];
    }
}
