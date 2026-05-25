<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@psu.edu.ph',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
            'force_password_change' => false,
        ]);

    }
}

// user "miashielagraceuson@gmail.com"
// pass " ganda123"