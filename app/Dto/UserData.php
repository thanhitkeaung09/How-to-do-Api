<?php

namespace App\Dto;
class UserData implements Dto
{
    public function __construct(
        public readonly string $name,
        public readonly string $profile,
        public readonly string $socialId,
        public readonly string $socialType,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $deviceToken = null,
    )
    {
    }
    public static function fromRequest(array $data, string $type): self
    {
        return new static(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'],
            profile: $data['profile_image'],
            socialId: $data['social_id'],
            socialType: $type,
            deviceToken: $data['device_token']
        );
    }

    public function toArray(): array
    {
        return [
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'social_id'=>$this->socialId,
            'social_type'=>$this->socialType,
            'device_token'=>$this->deviceToken,
        ];
    }
}
