<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class EditProfile extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'username' => 'required|unique:users|sometimes',
            'email' => 'required|email|unique:users|sometimes',
            'dob' => 'required',
            'gender' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'username.required' => 'Username is required',
            'username.unique' => 'Username must be unique',
            'email.required' => 'Email is required',
            'email.unique' => 'Email must be unique',
            'dob.required' => 'Date of Birth is required',
            'gender.required' => 'Gender is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
