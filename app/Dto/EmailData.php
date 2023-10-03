<?php

declare(strict_types=1);

namespace App\Dto;

class EmailData
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }

    public static function of(array $data): EmailData
    {
        return new EmailData(
            email: $data['email'],
            password: $data['password']
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
