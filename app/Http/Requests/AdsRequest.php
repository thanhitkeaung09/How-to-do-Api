<?php

namespace App\Http\Requests;

use App\Dto\AdsData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class AdsRequest extends FormRequest
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
            "admin_id" => ['required'],
            "ad_image" => ['sometimes', File::image()->types(['jpg', 'jpeg', 'png'])],
            "ad_link" => ['required'],
            "is_active" => ['required']
        ];
    }

    public function payload(): AdsData
    {
        return  AdsData::of([
            "admin_id" => $this->admin_id,
            "ad_image" => $this->ad_image,
            "ad_link" => $this->ad_link,
            "is_active" => $this->is_active
        ]);
    }
}
