<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // atau assign manual pas create
            'title' => $this->faker->sentence(),
            'is_done' => $this->faker->boolean(40), // 40% selesai
        ];
    }
}

