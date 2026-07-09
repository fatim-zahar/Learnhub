<?php

namespace Database\Seeders;

use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialities = [
            ['title' => 'Mathematics'],
            ['title' => 'Physics'],
            ['title' => 'Chemistry'],
            ['title' => 'Biology'],
            ['title' => 'Computer Science'],
            ['title' => 'Information Technology'],
            ['title' => 'Data Science & AI'],
            ['title' => 'Engineering'],
            ['title' => 'Languages'],
            ['title' => 'Literature'],
            ['title' => 'History'],
            ['title' => 'Geography'],
            ['title' => 'Economics'],
            ['title' => 'Business & Management'],
            ['title' => 'Accounting & Finance'],
            ['title' => 'Law'],
            ['title' => 'Exam Preparation'],
            ['title' => 'Study Skills'],
            ['title' => 'Soft Skills'],
            ['title' => 'Arts & Design'],
        ];

        Speciality::query()->insert($specialities);
    }
}
