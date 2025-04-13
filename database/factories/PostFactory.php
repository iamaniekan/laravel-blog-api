<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->text(120),
            'content' => fake()->paragraphs(5, true),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'status' => 'published',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
