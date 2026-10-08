<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-45 days', 'now');

        return [
            'user_id'          => User::factory(),
            'code'             => 'INV-' . $date->format('ymd') . '-' . Str::upper(Str::random(5)),
            // lebih banyak yang selesai/dikirim agar dashboard terlihat realistis
            'status'           => fake()->randomElement([
                'pending', 'paid', 'paid', 'shipped', 'shipped', 'completed', 'completed', 'completed', 'cancelled',
            ]),
            'shipping_name'    => fake()->name(),
            'shipping_address' => fake()->address(),
            'created_at'       => $date,
            'updated_at'       => $date,
        ];
    }
}
