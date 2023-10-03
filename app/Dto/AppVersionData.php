<?php

namespace App\Dto;

use Hamcrest\Type\IsBoolean;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class AppVersionData implements Dto
{
    public function __construct(
        public readonly string $version,
        public readonly string $build_number,
        public readonly string $android_link,
        public readonly string $ios_link,
        public readonly string $is_force_update

    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new static(
            version: $data['version'],
            build_number: $data['build_number'],
            android_link: $data['android_link'],
            ios_link: $data['ios_link'],
            is_force_update: $data['is_force_update']
        );
    }

    public function toArray(): array
    {
        return [
            "version" => $this->version,
            "build_number" => $this->build_number,
            "android_link" => $this->android_link,
            "ios_link" => $this->ios_link,
            "is_force_update" => $this->is_force_update
        ];
    }
}
