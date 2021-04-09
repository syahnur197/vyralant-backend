<?php

namespace App\Http\Resources\Comments;

use App\Http\Resources\Posts\PosterResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'posted_at' => $this->posted_at,
            'poster' => new PosterResource($this->poster),
            // 'comments' => self::collection($this->comments),

        ];
    }
}
