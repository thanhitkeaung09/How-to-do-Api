<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppVersion>
 */
class AppVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "version" => "1.2",
            "build_number" => "abcdefg",
            "android_link" => "www.google.com",
            "ios_link" => "www.facebook.com",
            "is_force_update" => false
        ];
    }
}
