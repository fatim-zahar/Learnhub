<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->create(['role' => 'student'])->each(function ($user) {
            Student::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
