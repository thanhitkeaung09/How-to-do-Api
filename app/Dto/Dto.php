<?php

declare(strict_types=1);

namespace App\Dto;

interface Dto
{
    public function toArray(): array;
}
