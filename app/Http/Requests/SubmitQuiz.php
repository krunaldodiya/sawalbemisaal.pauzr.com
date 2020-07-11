<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SubmitQuiz extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quiz_id' => 'required',
            'meta' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'quiz_id.required' => 'Quiz ID is required',
            'meta.required' => 'Meta is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
