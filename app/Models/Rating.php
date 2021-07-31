<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'upvotes',
        'downvotes',
        'ratings',
        'decayed_ratings',
    ];

    public function rateable()
    {
        return $this->morphTo();
    }
}
