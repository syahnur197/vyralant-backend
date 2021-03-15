<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        //
    }

    public function creating(Post $post)
    {
        $post->slug = Str::slug($post->title, '-');

        if (!isset($post->image)) {

            // temporary solution
            $images = [
                "/posts/1.jpg",
                "/posts/2.jpg",
                "/posts/3.jpg",
                "/posts/4.jpg",
                "/posts/5.jpg",
                "/posts/6.jpg",
                "/posts/7.jpg",
                "/posts/8.jpg",
            ];

            $post->image = collect($images)->random();
        }
    }

    public function saving(Post $post)
    {
        $post->category  = Str::lower($post->category);
        $post->post_type = Str::lower($post->post_type);
    }

    /**
     * Handle the Post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}
