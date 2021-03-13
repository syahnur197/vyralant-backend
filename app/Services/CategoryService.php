<?php

namespace App\Services;

use Illuminate\Support\Str;

class CategoryService
{
    const CATEGORIES = [
        'brunei',
        'world',
        'entertainment',
        'lifestyle',
        'technology',
        'memes',
    ];

    public function getCategories(): array
    {
        return self::CATEGORIES;
    }

    public function categoryExist($category): bool
    {
        return in_array(Str::lower($category), self::CATEGORIES);
    }

    public function getRandomCategory(): string
    {
        return collect(self::CATEGORIES)->random();
    }
}
