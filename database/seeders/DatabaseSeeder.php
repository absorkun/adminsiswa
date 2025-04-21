<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'role' => 'admin',
        ]);

        $subjects = [
            ['name' => 'Al Qur`an Hadis'],
            ['name' => 'Akidah Akhlak'],
            ['name' => 'Fikih'],
            ['name' => 'Sejarah Kebudayaan Islam'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan'],
            ['name' => 'Bahasa Indonesia'],
            ['name' => 'Bahasa Arab'],
            ['name' => 'Matematika'],
            ['name' => 'Ilmu Pengetahuan Alam'],
            ['name' => 'Ilmu Pengetahuan Sosial'],
            ['name' => 'Bahasa Inggris'],
            ['name' => 'Seni Budaya'],
            ['name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan'],
            ['name' => 'Informatika'],
            ['name' => 'Baca Tulis Al-Qur`an'],
        ];

        Subject::insert($subjects);


        // User::factory(10)->create();
        // Student::factory(10)->create();
        // Teacher::factory(10)->create();
        // Grade::factory(10)->create();
        // Attendance::factory(10)->create();
        // Schedule::factory(10)->create();
    }
}
