<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'category_id' => Category::factory(),
            'name'        => ucwords(fake()->unique()->words(3, true)),
            'description' => fake()->paragraph(),
            'price'       => fake()->numberBetween(20, 2000) * 1000,
            'stock'       => fake()->numberBetween(5, 100),
            'emoji'       => fake()->randomElement(['📦', '🎁', '🛍️']),
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
