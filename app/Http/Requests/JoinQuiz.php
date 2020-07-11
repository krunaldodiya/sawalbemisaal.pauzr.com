<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class JoinQuiz extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required',
            'quiz_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID is required',
            'quiz_id.required' => 'Quiz ID is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
