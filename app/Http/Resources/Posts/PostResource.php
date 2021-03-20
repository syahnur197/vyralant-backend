<?php

namespace App\Http\Resources\Posts;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // remove the id in the url whenever I use image from the fake_posts folder
        $image = optional($this->media[0])->getFullUrl();

        if (Str::contains($image, 'fake_posts')) {
            $image = Str::replaceFirst('/' . $this->media[0]->id, '', $image);
        }

        return [
            'id' => $this->id,
            'category' => $this->category,
            'post_type' => $this->post_type,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'upvotes_count' => $this->upvotes_count,
            'downvotes_count' => $this->downvotes_count,
            'rating' => $this->rating,
            'posted_at' => $this->posted_at,
            'excerpt' => $this->excerpt,
            'poster' => new PosterResource($this->poster),
            'image' => $image,
        ];
    }
}
