<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@bloomingkembang.com'],
            [
                'name' => 'Super Admin BloomingKembang',
                'phone' => '6281234567890',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $admin->assignRole('super_admin');

        $staff = User::firstOrCreate(
            ['email' => 'staff@bloomingkembang.com'],
            [
                'name' => 'Staf Operasional',
                'phone' => '6281234567891',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $staff->assignRole('staff');
    }
}
