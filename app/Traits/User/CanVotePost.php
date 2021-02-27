<?php

namespace App\Traits\User;

use App\Models\Post;
use App\Models\Vote;

trait CanVotePost
{

    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }

    public function upvote(Post $post): Vote
    {
        return $this->votes()->create([
            'post_id' => $post->id,
            'vote_type' => Vote::UPVOTE,
        ]);
    }

    public function downvote(Post $post): Vote
    {
        return $this->votes()->create([
            'post_id' => $post->id,
            'vote_type' => Vote::DOWNVOTE,
        ]);
    }

    public function hasVoted($post)
    {
        return $this->votes()->where([
            'post_id' => $post->id,
        ])->exists();
    }
}
