<?php

namespace App\Services;

use App\Models\Vote;

class VoteTypeService
{
    const VOTE_TYPE = [
        Vote::UPVOTE,
        Vote::DOWNVOTE,
    ];

    public function getVoteTypes(): array
    {
        return self::VOTE_TYPE;
    }

    public function VoteTypeExist($post_type): bool
    {
        return in_array($post_type, self::VOTE_TYPE);
    }

    public function getRandomVoteType(): string
    {
        return collect(self::VOTE_TYPE)->random();
    }
}
