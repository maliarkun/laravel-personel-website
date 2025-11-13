<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'member', // Varsayılan rol kolonunu geriye dönük uyumluluk için dolduruyoruz.
            'username' => $this->faker->unique()->userName(),
            'bio' => $this->faker->sentence(12),
            'timezone' => $this->faker->timezone(),
            'is_locked' => false,
            'remember_token' => Str::random(10),
        ];
    }
}
