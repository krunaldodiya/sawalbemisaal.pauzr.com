<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class JoinBulkQuiz extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quiz_id' => 'required',
            'total_participants' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'quiz_id.required' => 'Quiz ID is required',
            'total_participants.required' => 'Total Participants is required',
            'total_participants.number' => 'Total Participants field must be a number',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
