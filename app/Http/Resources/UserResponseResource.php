<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $request->id,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "social_id" => $request->social_id,
            "social_type" => $request->social_type,
            "profile_image" => $request->profile_image,
            "language" => $this->language,
            "device_token" => $this->device_token,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
            "token" => $this->token
        ];
    }
}
