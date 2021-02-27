<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

        return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', Rule::unique($users_table, 'email'), 'min:3', 'max:50'],
            'username' => ['required', 'string', 'alpha_dash', Rule::unique($users_table, 'username'), 'min:3', 'max:30'],
            'password' => ['required', 'confirmed', 'min:8'],
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
            'username.alpha_dash' => 'Username must only contain alphabets, numbers, dashes, or underscore',

            'password.required' => 'Please enter your password',
            'password.confirmed' => 'Passwords do not match!',
            'password.min' => 'Your password must be more than 8 characters',
        ];
    }
}
