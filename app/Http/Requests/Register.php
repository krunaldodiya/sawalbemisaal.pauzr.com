<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Register extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_id' => 'required',
            'mobile' => 'required|unique:users,mobile',
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|min:8|unique:users,username',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'Country is required',
            'mobile.required' => 'Mobile is required',
            'name.required' => 'Full Name is required',
            'email.required' => 'Email is required',
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
