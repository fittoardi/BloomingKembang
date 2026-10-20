<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Voucher;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PricingService;
use App\Services\Shipping\ShippingProviderInterface;
use App\Services\VoucherService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CheckoutPage extends Component
{
    // Data Pemesan
    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:190')]
    public string $email = '';

    #[Validate('required|string|max:20')]
    public string $phone = '';

    // Alamat Pengiriman
    public ?int $selectedAddressId = null;

    public bool $isNewAddress = false;

    #[Validate('required_if:isNewAddress,true|string|max:120')]
    public string $recipient_name = '';

    #[Validate('required_if:isNewAddress,true|string|max:20')]
    public string $recipient_phone = '';

    #[Validate('required_if:isNewAddress,true|string|max:100')]
    public string $province = 'Jawa Barat';

    #[Validate('required_if:isNewAddress,true|string|max:100')]
    public string $city = 'Bandung';

    #[Validate('required_if:isNewAddress,true|string|max:100')]
    public string $district = 'Coblong';

    #[Validate('required_if:isNewAddress,true|string|max:10')]
    public string $postal_code = '40132';

    #[Validate('required_if:isNewAddress,true|string|max:500')]
    public string $detail = '';

    // Kurir & Ongkir
    public array $shippingRates = [];

    #[Validate('required|string')]
    public string $selectedCourierKey = ''; // e.g. "JNE|REG|10000"

    // Voucher
    public string $voucherCodeInput = '';

    public ?string $appliedVoucherCode = null;

    public ?string $voucherError = null;

    public ?string $voucherSuccess = null;

    // Catatan
    public string $customerNote = '';

    public function mount(CartService $cartService, ShippingProviderInterface $shippingProvider): void
    {
        if ($cartService->getCount() <= 0) {
            $this->redirect(route('catalog.index'));

            return;
        }

        $user = Auth::user();

        if (! $user && ! (bool) Setting::get('checkout.guest_enabled', true)) {
            session()->flash('status', 'Silakan masuk atau daftar terlebih dahulu untuk melanjutkan pemesanan.');
            $this->redirect(route('login'));

            return;
        }

        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone ?? '';

            $defaultAddr = $user->defaultAddress ?? $user->addresses()->first();

            if ($defaultAddr) {
                $this->selectedAddressId = $defaultAddr->id;
                $this->recipient_name = $defaultAddr->recipient_name;
                $this->recipient_phone = $defaultAddr->phone;
                $this->province = $defaultAddr->province;
                $this->city = $defaultAddr->city;
                $this->district = $defaultAddr->district;
                $this->postal_code = $defaultAddr->postal_code;
                $this->detail = $defaultAddr->detail;
            } else {
                $this->isNewAddress = true;
                $this->recipient_name = $user->name;
                $this->recipient_phone = $user->phone ?? '';
            }
        } else {
            $this->isNewAddress = true;
        }

        $this->loadShippingRates($cartService, $shippingProvider);
    }

    public function selectAddress(int $addressId, CartService $cartService, ShippingProviderInterface $shippingProvider): void
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($addressId);

        $this->selectedAddressId = $address->id;
        $this->isNewAddress = false;
        $this->recipient_name = $address->recipient_name;
        $this->recipient_phone = $address->phone;
        $this->province = $address->province;
        $this->city = $address->city;
        $this->district = $address->district;
        $this->postal_code = $address->postal_code;
        $this->detail = $address->detail;

        $this->loadShippingRates($cartService, $shippingProvider);
    }

    public function toggleNewAddress(CartService $cartService, ShippingProviderInterface $shippingProvider): void
    {
        $this->isNewAddress = true;
        $this->selectedAddressId = null;
        $this->loadShippingRates($cartService, $shippingProvider);
    }

    public function updatedCity(CartService $cartService, ShippingProviderInterface $shippingProvider): void
    {
        $this->loadShippingRates($cartService, $shippingProvider);
    }

    public function updatedProvince(CartService $cartService, ShippingProviderInterface $shippingProvider): void
    {
        $this->loadShippingRates($cartService, $shippingProvider);
    }

    public function loadShippingRates(CartService $cartService, ShippingProviderInterface $shippingProvider): void
    {
        $destination = [
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'postal_code' => $this->postal_code,
        ];

        $totalWeight = $cartService->getTotalWeightGram();
        $this->shippingRates = $shippingProvider->calculateRates($destination, $totalWeight);

        if (! empty($this->shippingRates) && empty($this->selectedCourierKey)) {
            $first = $this->shippingRates[0];
            $this->selectedCourierKey = "{$first['courier']}|{$first['service']}|{$first['cost']}";
        }
    }

    public function applyVoucher(CartService $cartService, VoucherService $voucherService): void
    {
        $this->reset(['voucherError', 'voucherSuccess']);
        $code = trim($this->voucherCodeInput);

        if (empty($code)) {
            $this->voucherError = 'Silakan masukkan kode voucher.';

            return;
        }

        try {
            $subtotal = $cartService->getSubtotal();
            $voucher = $voucherService->validate($code, $subtotal, Auth::id());
            $this->appliedVoucherCode = $voucher->code;
            $this->voucherSuccess = "Voucher {$voucher->code} berhasil digunakan.";
            $this->voucherCodeInput = '';
        } catch (Exception $e) {
            $this->voucherError = $e->getMessage();
            $this->appliedVoucherCode = null;
        }
    }

    public function removeVoucher(): void
    {
        $this->appliedVoucherCode = null;
        $this->voucherSuccess = null;
        $this->voucherError = null;
    }

    public function getSelectedShippingCost(): int
    {
        if (empty($this->selectedCourierKey)) {
            return 0;
        }

        $parts = explode('|', $this->selectedCourierKey);

        return isset($parts[2]) ? (int) $parts[2] : 0;
    }

    public function placeOrder(CheckoutService $checkoutService): void
    {
        $this->validate();

        $courierParts = explode('|', $this->selectedCourierKey);
        $courier = $courierParts[0] ?? 'JNE';
        $courierService = $courierParts[1] ?? 'REG';
        $shippingCost = isset($courierParts[2]) ? (int) $courierParts[2] : 0;

        $customerData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        $shippingAddress = [
            'recipient_name' => $this->recipient_name,
            'phone' => $this->recipient_phone,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'postal_code' => $this->postal_code,
            'detail' => $this->detail,
        ];

        try {
            $order = $checkoutService->createOrder(
                $customerData,
                $shippingAddress,
                $courier,
                $courierService,
                $shippingCost,
                $this->appliedVoucherCode,
                $this->customerNote ?: null
            );

            session()->flash('status', "Pesanan #{$order->number} berhasil dibuat. Silakan selesaikan pembayaran.");
            $this->redirect(route('checkout.success', ['orderNumber' => $order->number]));
        } catch (Exception $e) {
            $this->addError('checkout_error', $e->getMessage());
        }
    }

    public function render(CartService $cartService, PricingService $pricingService): View
    {
        $subtotal = $cartService->getSubtotal();
        $items = $cartService->getItems();
        $totalWeight = $cartService->getTotalWeightGram();

        $voucher = null;
        if ($this->appliedVoucherCode) {
            $voucher = Voucher::where('code', $this->appliedVoucherCode)->first();
        }

        $shippingCost = $this->getSelectedShippingCost();
        $pricing = $pricingService->calculate($subtotal, $voucher, $shippingCost);

        $savedAddresses = Auth::check() ? Auth::user()->addresses()->orderByDesc('is_default')->get() : collect();

        return view('livewire.checkout-page', [
            'items' => $items,
            'subtotal' => $subtotal,
            'totalWeight' => $totalWeight,
            'pricing' => $pricing,
            'savedAddresses' => $savedAddresses,
        ])->layout('layouts.storefront', [
            'title' => 'Checkout Pembayaran — BloomingKembang',
        ]);
    }
}
