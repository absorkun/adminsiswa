<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class' => fake()->randomElement(['X IPA 1', 'XI IPS 2', 'XII Bahasa']),
            'subject_id' => Subject::factory(),
            'teacher_id' => Teacher::factory(),
            'day' => fake()->randomElement(['senin', 'selasa', 'rabu', 'kamis', 'jumat']),
            'time' => fake()->time(),
        ];

    }
}
