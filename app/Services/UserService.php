<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;

class UserService
{
    public function uploadProfilePicture(User $user)
    {
        $number = Arr::random([1, 2, 3, 4, 5, 6, 7, 8]);
        $user->media()->create([
            'collection_name' => 'profile_picture',
            'name' => 'fake_users/' . $number . '.jpg',
            'file_name' => 'fake_users/' . $number . '.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 's3',
            'conversions_disk' => 's3',
            'size' => 0,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'order_column' => 0,
        ]);
    }
}
