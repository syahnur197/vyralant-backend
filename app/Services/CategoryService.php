<?php

namespace App\Services;

class CategoryService
{
    const CATEGORIES = [
        'Brunei',
        'World',
        'Entertainment',
        'Lifestyle',
        'Technology',
        'Memes',
    ];

    public function getCategories(): array
    {
        return self::CATEGORIES;
    }

    public function categoryExist($category): bool
    {
        return in_array($category, self::CATEGORIES);
    }

    public function getRandomCategory(): string
    {
        return collect(self::CATEGORIES)->random();
    }
}
