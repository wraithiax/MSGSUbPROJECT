<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default degrees
        Degree::create(['Degree' => 'BS Information Technology']);
        Degree::create(['Degree' => 'BS Computer Science']);
        Degree::create(['Degree' => 'BS Information System']);
        Degree::create(['Degree' => 'BS Computer Engineering']);

        // Call UserSeeder
        $this->call(UserSeeder::class);
    }
}
