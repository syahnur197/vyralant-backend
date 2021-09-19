<?php

namespace App\Services;

use Illuminate\Support\Str;

class CategoryService
{
    const CATEGORIES = [
        'brunei',
        'world',
        'entertainment',
        'culture',
        'lifestyle',
        'nature',
        'fun',
        'technology',
        'sport',
    ];

    public static function getCategories(): array
    {
        return self::CATEGORIES;
    }

    public static function categoryExist($category): bool
    {
        return in_array(Str::lower($category), self::CATEGORIES);
    }

    public static function getRandomCategory(): string
    {
        return collect(self::CATEGORIES)->random();
    }
}
