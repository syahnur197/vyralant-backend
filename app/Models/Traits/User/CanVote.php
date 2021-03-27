<?php

namespace App\Models\Traits\User;

use App\Interfaces\VoteableInterface;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanVote
{

    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(Vote::class, 'user_id')
            ->whereUpvote();
    }

    public function downvotes(): HasMany
    {
        return $this->hasMany(Vote::class, 'user_id')
            ->whereDownvote();
    }

    public function upvote(VoteableInterface $voteable): Vote
    {
        $vote_query = $voteable->votes()->where('user_id', $this->id);

        $vote_type = Vote::UPVOTE;

        $vote = null;

        if (!$vote_query->exists()) {
            $vote = $voteable->votes()->create([
                'user_id' => $this->id,
                'vote_type' => $vote_type,
            ]);
        } else {
            /** @var Vote $vote */
            $vote = $vote_query->first();

            if ($vote->isUpvote()) {
                $vote_query->delete();
            } else if ($vote->isDownvote()) {
                $vote->update([
                    'user_id' => $this->id,
                    'vote_type' => $vote_type,
                ]);
            }
        }

        $voteable->calculateRatings();

        return $vote;
    }

    public function downvote(VoteableInterface $voteable): Vote
    {
        $vote_query = $voteable->votes()->where('user_id', $this->id);

        $vote_type = Vote::DOWNVOTE;

        $vote = null;

        if (!$vote_query->exists()) {
            $vote = $voteable->votes()->create([
                'user_id' => $this->id,
                'vote_type' => $vote_type,
            ]);
        } else {
            /** @var Vote $vote */
            $vote = $vote_query->first();

            if ($vote->isDownvote()) {
                $vote_query->delete();
            } else if ($vote->isUpvote()) {
                $vote->update([
                    'user_id' => $this->id,
                    'vote_type' => $vote_type,
                ]);
            }
        }

        $voteable->calculateRatings();

        return $vote;
    }

    public function hasVoted($post)
    {
        return $this->votes()->where([
            'post_id' => $post->id,
        ])->exists();
    }
}
