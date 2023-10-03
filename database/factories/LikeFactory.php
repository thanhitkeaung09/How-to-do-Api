<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "likeable_type" => Post::class,
            "likeable_id" => Post::factory(),
            "user_id" => User::pluck("id")->random()
        ];
    }

    public function toComments()
    {
        return $this->state(fn (array $attributes) => [
            'likeable_type' => Comment::class,
            'likeable_id' => Comment::factory(),
        ]);
    }
}
