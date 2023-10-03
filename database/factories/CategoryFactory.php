<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $array = ['Personal Development','Productivity','Psychology','Society, Arts & Culture','Cybersecurity','Relogion','Creativity','Habits','Money and Investment','Crytocurrency','Love & Relationships','Carrers'];
        return [
            "name"=>$array[array_rand($array)],
            "icon"=>fake()->imageUrl(),
            "color"=>fake()->hexColor
        ];
    }
}
