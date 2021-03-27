<?php

namespace App\Models;

use App\Interfaces\VoteableInterface;
use App\Models\Traits\HasVotes;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia, VoteableInterface
{
    use UsesUuid,
        HasFactory,
        HasVotes,
        InteractsWithMedia;

    protected $fillable = ['posted_by', 'title', 'slug', 'content', 'post_type', 'category'];

    protected $appends = ['posted_at', 'excerpt'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'posted_by',
    ];

    // relationships

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    // methods

    public function hasComments()
    {
        return $this->comments()->exists();
    }

    // accessors

    public function getPostedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getExcerptAttribute()
    {
        return Str::words($this->content, 20);
    }

    // scopes

    public function scopeWithRating(Builder $query)
    {
        // Laravel Performance Pattern FTW!!!
        $query->addSelect('*', 'upvotes_count', 'downvotes_count', DB::raw('(upvotes_count)/(upvotes_count+downvotes_count) as rating'));
        $query->from(function ($query) {
            $query->addSelect('*');
            $query->addSelect(DB::raw('(SELECT COUNT(*) FROM `votes` AS `uv` WHERE `uv`.`voteable_type` = "App\\\Models\\\Post" AND `uv`.`voteable_id` = `posts`.`id` AND `uv`.`vote_type` = "upvote" ) AS upvotes_count'));
            $query->addSelect(DB::raw('(SELECT COUNT(*) FROM `votes` AS `dv` WHERE `dv`.`voteable_type` = "App\\\Models\\\Post" AND `dv`.`voteable_id` = `posts`.`id` AND `dv`.`vote_type` = "downvote" ) AS downvotes_count'));
            $query->from('posts');
        }, 'posts');
    }

    // media library configs

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->useDisk('s3')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);
    }
}
