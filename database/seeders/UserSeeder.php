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
        User::updateOrCreate(
            ['email' => 'admin@psu.edu.ph'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'force_password_change' => false,
            ]
        );

    }
}

// user "miashielagraceuson@gmail.com"
// pass " ganda123"
