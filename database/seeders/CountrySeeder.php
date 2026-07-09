<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'India', 'code' => 'in'],
            ['name' => 'China', 'code' => 'cn'],
            ['name' => 'United States', 'code' => 'us'],
            ['name' => 'Indonesia', 'code' => 'id'],
            ['name' => 'Pakistan', 'code' => 'pk'],
            ['name' => 'Nigeria', 'code' => 'ng'],
            ['name' => 'Brazil', 'code' => 'br'],
            ['name' => 'Bangladesh', 'code' => 'bd'],
            ['name' => 'Russia', 'code' => 'ru'],
            ['name' => 'Mexico', 'code' => 'mx'],
        ];

        Country::query()->insert($countries);
    }
}
