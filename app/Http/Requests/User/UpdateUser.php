<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUser extends FormRequest
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

        $password_rule = Password::min(12)
            ->letters()
            ->mixedCase()
            ->numbers();

        return [
            'password' => ['string', 'nullable', 'confirmed', $password_rule],
        ];
    }
}
