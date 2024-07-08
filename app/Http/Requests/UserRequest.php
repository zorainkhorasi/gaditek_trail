<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username'=> 'required|unique:users|min:5|max:20',
            'password' => 'required|min:6|max:255',
            'email' => 'required|email|unique:users'
        ];
    }

    public function messages()
    {
       return [
           'username.required' => 'Username is required',
           'username.unique' => 'Username is already taken',
           'username.min' => 'Username must be at least 5 characters',
           'username.max' => 'Username must be less than 20 characters',
           'password.required' => 'Password is required',
           'password.min' => 'Password must be at least 6 characters',
           'password.max' => 'Password must be less than 255 characters',
           'email.required' => 'Email is required',
           'email.email' => 'Email is invalid',
           'email.unique' => 'Email is already taken'
       ];
    }
}
