<?php

use App\Enums\VoucherType;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\VoucherService;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('voucher service correctly calculates percentage and fixed discounts', function () {
    $service = app(VoucherService::class);

    // Persentase dengan max discount
    $voucherPercent = Voucher::create([
        'code' => 'DISKON10',
        'type' => VoucherType::Percentage,
        'value' => 10,
        'max_discount' => 25000,
        'is_active' => true,
    ]);

    expect($service->calculateDiscount($voucherPercent, 100000))->toBe(10000);
    expect($service->calculateDiscount($voucherPercent, 400000))->toBe(25000); // capped at max_discount

    // Fixed nominal
    $voucherFixed = Voucher::create([
        'code' => 'HEMAT20',
        'type' => VoucherType::Fixed,
        'value' => 20000,
        'is_active' => true,
    ]);

    expect($service->calculateDiscount($voucherFixed, 100000))->toBe(20000);
    expect($service->calculateDiscount($voucherFixed, 15000))->toBe(15000); // capped at subtotal
});

test('voucher validation rejects inactive, expired, and under-minimum vouchers', function () {
    $service = app(VoucherService::class);

    // Nonaktif
    Voucher::create([
        'code' => 'INAKTIF',
        'type' => VoucherType::Fixed,
        'value' => 10000,
        'is_active' => false,
    ]);

    expect(fn () => $service->validate('INAKTIF', 100000))
        ->toThrow(\RuntimeException::class, 'Voucher ini sudah tidak aktif.');

    // Kedaluwarsa
    Voucher::create([
        'code' => 'KADALUWARSA',
        'type' => VoucherType::Fixed,
        'value' => 10000,
        'is_active' => true,
        'ends_at' => now()->subDay(),
    ]);

    expect(fn () => $service->validate('KADALUWARSA', 100000))
        ->toThrow(\RuntimeException::class, 'Masa berlaku voucher telah berakhir.');

    // Min Belanja
    Voucher::create([
        'code' => 'MIN150',
        'type' => VoucherType::Fixed,
        'value' => 10000,
        'min_purchase' => 150000,
        'is_active' => true,
    ]);

    expect(fn () => $service->validate('MIN150', 100000))
        ->toThrow(\RuntimeException::class, 'Minimal pembelanjaan');
});

test('voucher validation rejects when quota or user limit is exhausted', function () {
    $service = app(VoucherService::class);
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    // Kuota habis
    $voucherQuota = Voucher::create([
        'code' => 'HABIS',
        'type' => VoucherType::Fixed,
        'value' => 10000,
        'quota' => 5,
        'used_count' => 5,
        'is_active' => true,
    ]);

    expect(fn () => $service->validate('HABIS', 100000))
        ->toThrow(\RuntimeException::class, 'Kuota penggunaan voucher telah habis.');

    // Limit per user
    $voucherUser = Voucher::create([
        'code' => 'SEKALI',
        'type' => VoucherType::Fixed,
        'value' => 10000,
        'per_user_limit' => 1,
        'is_active' => true,
    ]);

    // Record dummy usage for this user
    VoucherUsage::create([
        'voucher_id' => $voucherUser->id,
        'order_id' => 1,
        'user_id' => $customer->id,
        'discount_amount' => 10000,
    ]);

    expect(fn () => $service->validate('SEKALI', 100000, $customer->id))
        ->toThrow(\RuntimeException::class, 'Anda telah mencapai batas maksimal penggunaan voucher ini.');
});
