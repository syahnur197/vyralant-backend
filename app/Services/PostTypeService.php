<?php

namespace App\Services;

class PostTypeService
{
    const POST_TYPES = [
        'discussion',
        'video',
        'image',
        'link',
    ];

    public static function getPostTypes(): array
    {
        return self::POST_TYPES;
    }

    public static function postTypeExist($post_type): bool
    {
        return in_array($post_type, self::POST_TYPES);
    }

    public static function getRandomPostType(): string
    {
        return collect(self::POST_TYPES)->random();
    }
}
