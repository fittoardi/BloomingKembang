<?php

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);

    $this->customer = User::create([
        'name' => 'Fatimah Az-Zahra',
        'email' => 'fatimah@example.com',
        'phone' => '08123456789',
        'password' => 'password',
        'email_verified_at' => now(),
        'is_active' => true,
    ]);
    $this->customer->assignRole('customer');
});

test('super admin can access customer list and see customer data', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $response = $this->actingAs($admin)->get('/admin/customers');

    $response->assertSuccessful();
    $response->assertSee('Fatimah Az-Zahra');
});

test('staff can access customer list but cannot access customer edit page', function () {
    $staff = User::where('email', 'staff@bloomingkembang.com')->first();

    // Staff can view list
    $this->actingAs($staff)->get('/admin/customers')
        ->assertSuccessful();

    // Staff is forbidden from editing customer
    $this->actingAs($staff)->get("/admin/customers/{$this->customer->id}/edit")
        ->assertForbidden();
});

test('super admin can access customer edit page', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $this->actingAs($admin)->get("/admin/customers/{$this->customer->id}/edit")
        ->assertSuccessful();
});

test('super admin can access manage settings page', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $response = $this->actingAs($admin)->get('/admin/manage-settings');

    $response->assertSuccessful();
    $response->assertSee('Pengaturan Toko & Operasional');
});

test('staff cannot access manage settings page', function () {
    $staff = User::where('email', 'staff@bloomingkembang.com')->first();

    $response = $this->actingAs($staff)->get('/admin/manage-settings');

    $response->assertForbidden();
});

test('super admin can update store settings via ManageSettings component', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $this->actingAs($admin);

    Livewire::test(ManageSettings::class)
        ->fillForm([
            'store_name' => 'BloomingKembang Exclusive',
            'store_tagline' => 'Hijab Premium Berkualitas Tinggi',
            'store_whatsapp' => '628999888777',
            'store_email' => 'halo@bloomingkembang.com',
            'warehouse_province' => 'DKI Jakarta',
            'warehouse_city' => 'Jakarta Selatan',
            'warehouse_district' => 'Tebet',
            'warehouse_postal_code' => '12810',
            'warehouse_detail' => 'Jl. Tebet Barat Raya No. 45',
            'guest_checkout_enabled' => false,
            'payment_expiry_hours' => 12,
            'active_couriers' => ['jne', 'sicepat'],
            'ga4_id' => 'G-TESTING123',
            'meta_pixel_id' => '987654321012345',
        ])
        ->call('save')
        ->assertHasNoErrors();

    expect(Setting::get('store.name'))->toBe('BloomingKembang Exclusive')
        ->and(Setting::get('store.tagline'))->toBe('Hijab Premium Berkualitas Tinggi')
        ->and(Setting::get('store.whatsapp'))->toBe('628999888777')
        ->and(Setting::get('store.email'))->toBe('halo@bloomingkembang.com')
        ->and(Setting::get('checkout.guest_enabled'))->toBe(false)
        ->and(Setting::get('order.payment_expiry_hours'))->toBe(12)
        ->and(Setting::get('shipping.couriers'))->toEqual(['jne', 'sicepat'])
        ->and(Setting::get('analytics.ga4_id'))->toBe('G-TESTING123')
        ->and(Setting::get('analytics.meta_pixel_id'))->toBe('987654321012345');
});

test('super admin can access staff list and create staff page', function () {
    $admin = User::where('email', 'admin@bloomingkembang.com')->first();

    $this->actingAs($admin)->get('/admin/staff')
        ->assertSuccessful()
        ->assertSee('Staf & Tim');

    $this->actingAs($admin)->get('/admin/staff/create')
        ->assertSuccessful();
});

test('staff cannot access staff management pages', function () {
    $staff = User::where('email', 'staff@bloomingkembang.com')->first();

    $this->actingAs($staff)->get('/admin/staff')
        ->assertForbidden();

    $this->actingAs($staff)->get('/admin/staff/create')
        ->assertForbidden();
});
