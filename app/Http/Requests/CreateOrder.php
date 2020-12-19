<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class CreateOrder extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_id' => 'required',
            'transaction_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'plan_id.required' => 'Plan ID is required',
            'payment_id.required' => 'Payment ID is required',
            'status.required' => 'Status is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
