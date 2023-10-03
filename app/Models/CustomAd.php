<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomAd extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function adImage(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? route('api:v1:image:show', $value) : $value,
        );
    }
}
