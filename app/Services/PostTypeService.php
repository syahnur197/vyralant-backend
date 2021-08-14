<?php

namespace App\Services;

class PostTypeService
{

    const DISCUSSION = 'discussion';
    const VIDEO      = 'video';
    const IMAGE      = 'image';
    const LINK       = 'link';

    const POST_TYPES = [
        self::DISCUSSION,
        self::VIDEO,
        self::IMAGE,
        self::LINK,
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
