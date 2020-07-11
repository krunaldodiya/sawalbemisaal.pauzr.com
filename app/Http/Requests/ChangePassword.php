<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class ChangePassword extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|min:8',
            'confirm' => 'required|match:password',
        ];
    }

    public function messages()
    {
        return [
            'confirm.required' => 'Password is required',
            'confirm.match' => 'Password & Confirm must match',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
