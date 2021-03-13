<?php

namespace App\Rules;

use App\Services\CategoryService;
use Illuminate\Contracts\Validation\Rule;

class CategoryExist implements Rule
{

    private $service;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->service = app(CategoryService::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->service->categoryExist($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
