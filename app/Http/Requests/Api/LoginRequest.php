<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['nullable'],
            'phone' => ['nullable'],
            'social_id' => ['required', 'string'],
            'profile_image' => ['required', 'string'],
            'device_token' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Validation Failed!',
//            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
