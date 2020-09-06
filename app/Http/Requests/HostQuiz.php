<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class HostQuiz extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'total_participants' => 'required',
            'entry_fee' => 'required',
            'timings' => 'required',
            'private' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'total_participants.required' => 'Total Participants is required',
            'entry_fee.required' => 'Entry Fee is required',
            'expiry.required' => 'Expiry is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
