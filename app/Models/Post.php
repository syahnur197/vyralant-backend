<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Post extends Model
{
    use UsesUuid,
        HasFactory;

    protected $fillable = ['posted_by', 'title', 'slug', 'content', 'post_type', 'category'];

    protected $appends = ['posted_at', 'excerpt'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'posted_by',
    ];

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function upvotes()
    {
        return $this->hasMany(Vote::class, 'post_id')
            ->whereUpvote();
    }

    public function downvotes()
    {
        return $this->hasMany(Vote::class, 'post_id')
            ->whereDownvote();
    }

    public function getRatingAttribute($rating)
    {
        $rating = $rating * 100;

        $rating = round($rating, 1);

        $rating = number_format ( $rating, 1);

        return $rating;
    }

    public function getPostedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getExcerptAttribute()
    {
        return Str::words($this->content,20);
    }

    public function scopeWithRating(Builder $query)
    {
        // Laravel Performance Pattern FTW!!!
        $query->addSelect('*', 'upvotes_count', 'downvotes_count', DB::raw('(upvotes_count)/(upvotes_count+downvotes_count) as rating'));
        $query->from(function($query) {
            $query->addSelect('*');
            $query->addSelect(DB::raw('(SELECT COUNT(*) FROM `votes` AS `uv` WHERE `uv`.`vote_type` = "upvote" AND `uv`.`post_id` = `posts`.`id`) AS upvotes_count'));
            $query->addSelect(DB::raw('(SELECT COUNT(*) FROM `votes` AS `dv` WHERE `dv`.`vote_type` = "downvote" AND `dv`.`post_id` = `posts`.`id`) AS downvotes_count'));
            $query->from('posts');
        }, 'posts');
    }
}
