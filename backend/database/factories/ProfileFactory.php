<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->jobTitle(),
            'bio' => $this->faker->paragraph(),
            'hourly_rate' => $this->faker->randomFloat(2, 5, 150),
            'avatar_path' => null,
            'country' => $this->faker->country(),
            'timezone' => 'UTC',
            'avg_rating' => $this->faker->randomFloat(2, 3, 5),
        ];
    }
}
