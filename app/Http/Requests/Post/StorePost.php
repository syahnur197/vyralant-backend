<?php

namespace App\Http\Requests\Post;

use App\Rules\CategoryExist;
use App\Services\CategoryService;
use App\Services\PostTypeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category' => ['required', Rule::in(CategoryService::getCategories())],
            'title' => ['required', 'unique:posts,title'],
            'content' => ['required', 'max:3000'],
            'post_type' => ['required', Rule::in(PostTypeService::getPostTypes())],
            'image' => ['required_if:post_type,image', 'file', 'image', 'max:1024'],
            'link' => ['required_if:post_type,link'],
        ];
    }

    public function messages()
    {
        return [
            'category.required' => 'Please specify the post category!',
            'category.in' => 'Category does not exist!',

            'title.required' => 'Please provide the post title!',
            'title.unique' => 'Please provide a unique title!',

            'content.required' => 'Please provide the content',
            'content.max' => 'Content must not be more than 3000 characters',

            'post_type.required' => 'Please specify the post type',
            'post_type.in' => 'Post type provided is not valid!',

            'image.required_if' => 'Please upload an image',
            'image.image' => 'File must be an image (png, jpg)!',
            'image.max' => 'Image has to be less than 1 MB',

            'link.required_if' => 'Please specify a link',
        ];
    }
}
