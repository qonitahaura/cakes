<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Assign role
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        echo "Admin user created successfully!\n";
    }
}
