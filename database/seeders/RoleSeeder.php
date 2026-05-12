<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'baker',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'customer_service',
            'guard_name' => 'web'
        ]);
    }
}
