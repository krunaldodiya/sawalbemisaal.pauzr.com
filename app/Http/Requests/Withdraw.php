<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class Withdraw extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'gateway' => 'required',
            'mobile' => 'required',
            'amount' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'gateway.required' => 'Gateway is required',
            'mobile.required' => 'Mobile ID is required',
            'amount.required' => 'Amount ID is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
