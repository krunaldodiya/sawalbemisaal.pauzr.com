<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class GetQuestions extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required',
            'language_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Question ID is required',
            'language_id.required' => 'Language ID is required',
        ];
    }

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
