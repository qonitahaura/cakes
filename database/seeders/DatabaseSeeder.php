<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(StaffSeeder::class);

        // Demo/seed data for dashboards
        $this->call(ProductSeeder::class);
        $this->call(DemoCustomerSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(ReviewSeeder::class);
    }
}
