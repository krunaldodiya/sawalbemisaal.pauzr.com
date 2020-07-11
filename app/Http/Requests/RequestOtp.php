<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class RequestOtp extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_id' => 'required',
            'mobile' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'Country ID is required',
            'mobile.required' => 'Mobile is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
