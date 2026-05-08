<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $baker = User::firstOrCreate(
            ['email' => 'baker@gmail.com'],
            [
                'name' => 'Baker',
                'password' => Hash::make('baker123'),
            ]
        );
        if (! $baker->hasRole('baker')) {
            $baker->assignRole('baker');
        }

        $cs = User::firstOrCreate(
            ['email' => 'cs@gmail.com'],
            [
                'name' => 'Customer Service',
                'password' => Hash::make('cs123'),
            ]
        );
        if (! $cs->hasRole('customer_service')) {
            $cs->assignRole('customer_service');
        }

        $this->command?->info('Staff users: baker@gmail.com / baker123, cs@gmail.com / cs123');
    }
}
