<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Livewire\Account\AddressBook;
use App\Livewire\Account\WishlistIndex;
use App\Livewire\ProductShow;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Events\Registered;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(AdminSeeder::class);
    $this->seed(DemoSeeder::class);
});

test('customer can add and manage addresses in address book', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();

    $this->actingAs($customer);

    Livewire::test(AddressBook::class)
        ->call('openCreateModal')
        ->set('label', 'Kantor')
        ->set('recipient_name', 'Fatimah Az-Zahra')
        ->set('phone', '081234567890')
        ->set('province', 'DKI Jakarta')
        ->set('city', 'Jakarta Selatan')
        ->set('district', 'Kebayoran Baru')
        ->set('postal_code', '12150')
        ->set('detail', 'Gedung Kembang Lt. 3')
        ->set('is_default', true)
        ->call('save')
        ->assertHasNoErrors();

    $address = Address::where('user_id', $customer->id)->where('label', 'Kantor')->first();
    expect($address)->not->toBeNull();
    expect($address->is_default)->toBeTrue();

    // Ubah alamat
    Livewire::test(AddressBook::class)
        ->call('openEditModal', $address->id)
        ->set('detail', 'Gedung Kembang Lt. 5, Suite 502')
        ->call('save')
        ->assertHasNoErrors();

    expect($address->fresh()->detail)->toBe('Gedung Kembang Lt. 5, Suite 502');

    // Hapus alamat
    Livewire::test(AddressBook::class)
        ->call('deleteAddress', $address->id);

    expect(Address::where('id', $address->id)->exists())->toBeFalse();
});

test('customer cannot manage another customers address', function () {
    $customerA = User::where('email', 'customer@bloomingkembang.com')->first();
    $customerB = User::factory()->create();
    $customerB->assignRole('customer');

    $addressB = Address::create([
        'user_id' => $customerB->id,
        'label' => 'Rahasia B',
        'recipient_name' => 'Customer B',
        'phone' => '0899999999',
        'province' => 'Jawa Timur',
        'city' => 'Surabaya',
        'district' => 'Wonokromo',
        'postal_code' => '60241',
        'detail' => 'Jl. Merdeka No. 1',
        'is_default' => true,
    ]);

    Livewire::actingAs($customerA)
        ->test(AddressBook::class)
        ->call('openEditModal', $addressB->id)
        ->assertForbidden();
});

test('customer can toggle product wishlist and view in wishlist index', function () {
    $customer = User::where('email', 'customer@bloomingkembang.com')->first();
    $product = Product::where('status', ProductStatus::Active)->first();

    $this->actingAs($customer);

    Livewire::test(ProductShow::class, ['slug' => $product->slug])
        ->call('toggleWishlist');

    expect(Wishlist::where('user_id', $customer->id)->where('product_id', $product->id)->exists())->toBeTrue();

    // Wishlist index menampilkan produk
    Livewire::test(WishlistIndex::class)
        ->assertSee($product->name);

    // Toggle kembali untuk menghapus
    Livewire::test(ProductShow::class, ['slug' => $product->slug])
        ->call('toggleWishlist');

    expect(Wishlist::where('user_id', $customer->id)->where('product_id', $product->id)->exists())->toBeFalse();
});

test('guest order is automatically linked to user on registration with matching email', function () {
    // Buat guest order sebelum user mendaftar
    $guestEmail = 'newcustomer@example.com';
    $order = Order::create([
        'number' => 'ORD-GUEST-999',
        'user_id' => null,
        'customer_name' => 'Calon Member',
        'customer_email' => $guestEmail,
        'customer_phone' => '081234567899',
        'shipping_address' => ['detail' => 'Bandung'],
        'subtotal' => 150000,
        'grand_total' => 150000,
        'status' => OrderStatus::PendingPayment,
        'payment_status' => PaymentStatus::Pending,
        'total_weight_gram' => 200,
    ]);

    expect($order->user_id)->toBeNull();

    // User baru mendaftar dengan email yang sama
    $newUser = User::factory()->create([
        'email' => $guestEmail,
    ]);

    event(new Registered($newUser));

    // Order otomatis terhubung ke user id
    expect($order->fresh()->user_id)->toBe($newUser->id);
});
