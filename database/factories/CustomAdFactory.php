<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomAd>
 */
class CustomAdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "admin_id" => Admin::pluck("id")->random(),
            "ad_image" => fake()->imageUrl(),
            "is_active" => false,
            "ad_link" => fake()->url()
        ];
    }
}
