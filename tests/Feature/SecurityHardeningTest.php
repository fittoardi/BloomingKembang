<?php

use App\Enums\OrderStatus;
use App\Livewire\Account\AddressBook;
use App\Livewire\TrackOrder;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('it returns recommended security headers on storefront requests', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-XSS-Protection', '1; mode=block');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy');
});

test('it prevents IDOR on customer address updates across users', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $addressB = Address::create([
        'user_id' => $userB->id,
        'label' => 'Kantor User B',
        'recipient_name' => 'User B',
        'phone' => '081299990000',
        'province' => 'Jawa Barat',
        'city' => 'Bandung',
        'district' => 'Coblong',
        'postal_code' => '40132',
        'detail' => 'Gedung Sate',
        'is_default' => true,
    ]);

    // User A mencoba mengedit alamat User B via Livewire AddressBook
    $this->actingAs($userA);

    Livewire::test(AddressBook::class)
        ->call('openEditModal', $addressB->id)
        ->assertForbidden();

    Livewire::test(AddressBook::class)
        ->call('deleteAddress', $addressB->id)
        ->assertForbidden();
});

test('it prevents IDOR on customer order detail across users', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $orderB = Order::create([
        'user_id' => $userB->id,
        'number' => 'ORD-SEC-999',
        'customer_name' => 'User B',
        'customer_email' => $userB->email,
        'customer_phone' => '081299990000',
        'status' => OrderStatus::PendingPayment,
        'subtotal' => 150000,
        'discount_amount' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 160000,
        'shipping_address' => ['recipient_name' => 'User B'],
    ]);

    // User A mencoba melihat detail pesanan User B
    $this->actingAs($userA);

    $response = $this->get(route('account.orders.show', $orderB->number));
    $response->assertForbidden();
});

test('it requires matching contact info for guest tracking order', function () {
    $order = Order::create([
        'user_id' => null,
        'number' => 'ORD-GUEST-777',
        'customer_name' => 'Siti Khadijah',
        'customer_email' => 'siti@example.com',
        'customer_phone' => '081234567899',
        'status' => OrderStatus::PendingPayment,
        'subtotal' => 125000,
        'discount_amount' => 0,
        'shipping_cost' => 10000,
        'grand_total' => 135000,
        'shipping_address' => ['recipient_name' => 'Siti Khadijah', 'phone' => '081234567899'],
    ]);

    // Pencarian dengan kontak yang salah
    Livewire::test(TrackOrder::class)
        ->set('orderNumber', 'ORD-GUEST-777')
        ->set('contact', 'attacker@random.com')
        ->call('search')
        ->assertSee('Data kontak (email / nomor telepon) tidak cocok')
        ->assertSet('order', null);

    // Pencarian dengan nomor telepon yang benar
    Livewire::test(TrackOrder::class)
        ->set('orderNumber', 'ORD-GUEST-777')
        ->set('contact', '081234567899')
        ->call('search')
        ->assertDontSee('Data kontak (email / nomor telepon) tidak cocok')
        ->assertSet('order.number', 'ORD-GUEST-777');
});

test('it renders friendly custom 404 error page on missing route', function () {
    $response = $this->get('/halaman-yang-pasti-tidak-ada-random-xyz');

    $response->assertNotFound();
    $response->assertSee('Halaman Tidak Ditemukan');
    $response->assertSee('Blooming');
    $response->assertSee('Kembang');
    $response->assertSee('Kembali ke Beranda');
});
