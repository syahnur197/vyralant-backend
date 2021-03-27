<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{

    const UPVOTE   = 'upvote';
    const DOWNVOTE = 'downvote';

    protected $fillable = ['user_id', 'post_id', 'vote_type'];

    public function voteable()
    {
        return $this->morphTo();
    }

    public function isUpvote(): bool
    {
        return $this->vote_type == self::UPVOTE;
    }

    public function isDownvote(): bool
    {
        return $this->vote_type == self::DOWNVOTE;
    }

    public function scopeWhereUpvote($query)
    {
        return $query->where('vote_type', self::UPVOTE);
    }

    public function scopeWhereDownvote($query)
    {
        return $query->where('vote_type', self::DOWNVOTE);
    }
}
