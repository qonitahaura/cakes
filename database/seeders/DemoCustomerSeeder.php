<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'customer@cakes.com'],
            [
                'name' => 'Tira',
                'password' => Hash::make('password'), // Tira password

            ]
        );

        if (! $user->hasRole('customer')) {
            $user->assignRole('customer');
        }

        $this->command?->info('Demo customer ensured: customer@cakes.com / password');
    }
}
