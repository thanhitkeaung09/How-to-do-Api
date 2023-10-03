<?php

namespace App\Dto;

class RateData implements Dto
{
    public function __construct(
        public readonly string $rate,
        public readonly string $description,
        public readonly string $user_id
    )
    {
    }

    public static function fromRequest(array $data): self
    {
        return new static(
            rate: $data['rate'],
            description: $data['description'],
            user_id: auth()->id()
        );
    }

    public function toArray(): array
    {
        return [
            'rate'=>$this->rate,
            'description'=>$this->description,
            'user_id'=>$this->user_id
        ];
    }

}
