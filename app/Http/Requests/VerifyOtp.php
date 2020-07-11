<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class VerifyOtp extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_id' => 'required',
            'mobile' => 'required',
            'otp' => 'required|digits:4'
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'Country ID is required',
            'mobile.required' => 'Mobile is required',
            'otp.required' => 'Otp is required',
            'otp.digits' => 'Otp must be 4 digits',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
