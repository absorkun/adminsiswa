<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nisn' => fake()->unique()->numerify('########'),
            'class' => fake()->randomElement(['X IPA 1', 'XI IPS 2', 'XII Bahasa']),
            'phone' => fake()->phoneNumber(),
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
        ];
    }
}
