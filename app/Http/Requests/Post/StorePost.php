<?php

namespace App\Http\Requests\Post;

use App\Rules\CategoryExist;
use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => ['required', new CategoryExist],
            'title' => ['required', 'unique'],
            'content' => ['required'],
        ];
    }
}
