<?php

namespace App\Services;

class PostTypeService
{
    const POST_TYPES = [
        'Disussion',
        'Video',
        'Image',
        'Link',
    ];

    public function getPostTypes(): array
    {
        return self::POST_TYPES;
    }

    public function postTypeExist($post_type): bool
    {
        return in_array($post_type, self::POST_TYPES);
    }

    public function getRandomPostType(): string
    {
        return collect(self::POST_TYPES)->random();
    }
}
