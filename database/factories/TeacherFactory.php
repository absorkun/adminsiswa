<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
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
            'nip' => fake()->unique()->numerify('########'),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->randomElement(['Matematika', 'Bahasa Indonesia', 'Fisika']),
            'user_id' => User::factory()->create(['role' => 'guru'])->id,
        ];

    }
}
