<?php

namespace App\Http\Resources\Users;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image_url = optional($this->media[0])->getFullUrl();

        if (Str::contains($image_url, 'fake_users')) {
            $image_url = Str::replaceFirst('/' . $this->media[0]->id, '', $image_url);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'image_url' => $image_url,
        ];
    }
}
