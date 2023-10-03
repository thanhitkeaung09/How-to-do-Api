<?php

namespace App\Http\Requests;

use App\Dto\EmailData;
use Illuminate\Foundation\Http\FormRequest;

class EmailDataRequest extends FormRequest
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
            "email" => ['required'],
            "password" => ['required']
        ];
    }

    public function payload(): EmailData
    {
        return EmailData::of([
            "email" => $this->email,
            "password" => $this->password
        ]);
    }
}
