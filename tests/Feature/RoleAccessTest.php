<?php

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('guest is redirected when accessing admin panel', function () {
    $response = $this->get('/admin');

    // Filament returns redirect to login
    $response->assertRedirect();
});

test('customer cannot access admin panel', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    $response = $this->actingAs($customer)->get('/admin');

    // Customer should receive 403 Forbidden because canAccessPanel returns false
    $response->assertForbidden();
});

test('admin can access admin panel', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertSuccessful();
});

test('staff can access admin panel', function () {
    $staff = User::where('email', 'staff@bloomingkembang.com')->first();

    $response = $this->actingAs($staff)->get('/admin');

    $response->assertSuccessful();
});

test('authenticated customer can access account dashboard', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    $response = $this->actingAs($customer)->get('/akun');

    $response->assertSuccessful();
    $response->assertSee('Portal Customer');
    $response->assertSee('Fatimah Az-Zahra');
});

test('guest is redirected to login when accessing account dashboard', function () {
    $response = $this->get('/akun');

    $response->assertRedirect('/login');
});
