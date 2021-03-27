<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface VoteableInterface
{
    public function votes(): MorphMany;

    public function upvotes(): MorphMany;

    public function downvotes(): MorphMany;

    public function rating(): MorphOne;

    public function calculateRatings(): void;
}
