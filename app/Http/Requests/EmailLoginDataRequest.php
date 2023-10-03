<?php

namespace App\Http\Requests;

use App\Dto\EmailLoginData;
use Illuminate\Foundation\Http\FormRequest;

class EmailLoginDataRequest extends FormRequest
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
            "name" => ['required'],
            "email" => ['required'],
            "password" => ['required'],
            "confirm_password" => ['required']
        ];
    }

    public function payload(): EmailLoginData
    {
        return EmailLoginData::of([
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
            "confirm_password" => $this->confirm_password
        ]);
    }
}
