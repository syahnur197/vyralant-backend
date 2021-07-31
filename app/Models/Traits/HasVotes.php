<?php

namespace App\Models\Traits;

use App\Interfaces\VoteableInterface;
use App\Models\Rating;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasVotes
{
    // relationships

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function upvotes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable')
            ->whereUpvote();
    }

    public function downvotes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable')
            ->whereDownvote();
    }

    public function rating(): MorphOne
    {
        return $this->morphOne(Rating::class, 'rateable');
    }

    // methods

    public function calculateRatings(): void
    {
        $upvotes_count = $this->upvotes()->count();
        $downvotes_count = $this->downvotes()->count();
        $votes_count = $this->votes()->count();

        $ratings = $votes_count === 0
            ? 0.00
            : ($upvotes_count / $votes_count) * 100;

        $days = $this->created_at->diffInDays(now());

        $decayed_ratings = $ratings * pow(0.9, $days);

        $this->rating()->updateOrCreate([], [
            'upvotes' => $upvotes_count,
            'downvotes' => $downvotes_count,
            'ratings' => $ratings,
            'decayed_ratings' => $decayed_ratings,
        ]);
    }

    public function upvotedBy(?User $user)
    {
        if (!isset($user)) return false;

        return $this->upvotes()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function downvotedBy(?User $user)
    {
        if (!isset($user)) return false;

        return $this->downvotes()
            ->where('user_id', $user->id)
            ->exists();
    }
}
