<?php

namespace App\Enums;

enum Language: String
{
case MM = 'mm';

case EN = 'en';

    public static function values(): array
{
    return \array_map(fn ($value) => $value->value, self::cases());
}
}
