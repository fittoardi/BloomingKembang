<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\Component;

class TrackOrder extends Component
{
    #[Url(as: 'order')]
    public string $orderNumber = '';

    public string $contact = '';

    public ?Order $order = null;

    public ?string $errorMessage = null;

    public bool $isSigned = false;

    public function mount(Request $request, ?string $orderNumber = null): void
    {
        if ($orderNumber) {
            $this->orderNumber = $orderNumber;
        }

        // Cek jika diakses lewat signed URL
        if ($request->hasValidSignature() && $this->orderNumber !== '') {
            $this->isSigned = true;
            $this->loadOrder();
        } elseif ($this->orderNumber !== '' && $this->contact !== '') {
            $this->loadOrder();
        }
    }

    public function search(): void
    {
        $this->validate([
            'orderNumber' => ['required', 'string', 'min:3'],
            'contact' => ['required', 'string', 'min:3'],
        ], [
            'orderNumber.required' => 'Nomor pesanan wajib diisi.',
            'contact.required' => 'Email atau nomor telepon wajib diisi untuk verifikasi keamanan.',
        ]);

        $this->loadOrder();
    }

    protected function loadOrder(): void
    {
        $this->errorMessage = null;

        $order = Order::with(['items.variant.product', 'statusHistories'])
            ->where('number', trim($this->orderNumber))
            ->first();

        if (! $order) {
            $this->errorMessage = 'Pesanan dengan nomor tersebut tidak ditemukan.';
            $this->order = null;

            return;
        }

        if (! $this->isSigned) {
            $contactInput = strtolower(trim($this->contact));
            $emailMatch = strtolower($order->customer_email) === $contactInput;
            $phoneMatch = str_replace([' ', '-', '+'], '', $order->customer_phone) === str_replace([' ', '-', '+'], '', $contactInput);
            $shippingPhoneMatch = isset($order->shipping_address['phone']) && str_replace([' ', '-', '+'], '', $order->shipping_address['phone']) === str_replace([' ', '-', '+'], '', $contactInput);

            if (! $emailMatch && ! $phoneMatch && ! $shippingPhoneMatch) {
                $this->errorMessage = 'Data kontak (email / nomor telepon) tidak cocok dengan pesanan ini.';
                $this->order = null;

                return;
            }
        }

        $this->order = $order;
    }

    public function payNow(PaymentGatewayInterface $paymentGateway): void
    {
        if (! $this->order || $this->order->status->value !== 'pending_payment') {
            return;
        }

        try {
            $result = $paymentGateway->createTransaction($this->order);
            if (! empty($result['redirect_url'])) {
                $this->redirect($result['redirect_url']);
            }
        } catch (\Throwable $e) {
            $this->errorMessage = 'Gagal memproses pembayaran: '.$e->getMessage();
        }
    }

    public function render(): View
    {
        return view('pages.orders.track')->layout('layouts.storefront', [
            'title' => 'Lacak Pesanan — BloomingKembang',
        ]);
    }
}
