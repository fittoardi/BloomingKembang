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
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            DemoSeeder::class,
            OrderSeeder::class,
            FactorySeeder::class,
        ]);
    }
}
