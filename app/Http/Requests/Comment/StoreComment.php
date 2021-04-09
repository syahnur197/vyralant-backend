<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreComment extends FormRequest
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
            'content' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Please provide the content',
            'content.string' => 'Content must be a string',
            'content.max' => 'Content must be not more than 1000 characters',
        ];
    }

    public function validated()
    {
        $data = parent::validated();

        $data['posted_by'] = $this->user()->id;

        return $data;
    }
}
