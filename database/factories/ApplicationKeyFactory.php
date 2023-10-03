<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationKey>
 */
class ApplicationKeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition():array
    {
        return [
            'name' => fake()->unique()->name,
            'app_id' => generateAppId(),
            'app_secrete' => generateAppSecrete(),
            'obsoleted' => true,
        ];
    }
    public function notObsoleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'obsoleted' => false,
        ]);
    }
}
