<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $users_table = app(User::class)->getTable();

        $password_rule = Password::min(12)
            ->letters()
            ->mixedCase()
            ->numbers();

        return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', Rule::unique($users_table, 'email'), 'min:3', 'max:50'],
            'username' => ['required', 'string', 'alpha_num', Rule::unique($users_table, 'username'), 'min:3', 'max:30'],
            'password' => ['required', 'confirmed', $password_rule],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your name',
            'name.min' => 'Your name must be more than 3 characters',
            'name.max' => 'Your name must be be less than 50 characters',

            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter valid email address',
            'email.min' => 'Your email must be more than 3 characters',
            'email.max' => 'Your email must be be less than 50 characters',
            'email.unique' => 'Email has been taken',

            'username.required' => 'Please enter your username',
            'username.min' => 'Your username must be more than 3 characters',
            'username.max' => 'Your username must be be less than 30 characters',
            'username.unique' => 'Username has been taken',
            'username.alpha_num' => 'Username must only contain alphabets, and / or number',

            'password.required' => 'Please enter your password',
            'password.confirmed' => 'Passwords do not match!',
            'password.min' => 'Your password must be more than 8 characters',
        ];
    }
}
