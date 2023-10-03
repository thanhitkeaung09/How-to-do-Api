<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            "admin_id"=>Admin::pluck('id')->random(),
//            "user_id"=>User::pluck('id')->random(),
            "category_id"=>Category::pluck('id')->random(),
            'cover_image'=>fake()->imageUrl,
            'article_title'=>fake()->title,
            'short_words'=>fake()->word(20),
            'body_text_image'=>fake()->paragraph
        ];
    }
}
