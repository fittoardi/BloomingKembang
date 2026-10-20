<?php

namespace App\Services\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MidtransPaymentGateway implements PaymentGatewayInterface
{
    protected string $serverKey;

    protected string $clientKey;

    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key', '');
        $this->clientKey = (string) config('services.midtrans.client_key', '');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
    }

    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * @return array{snap_token: string, redirect_url: string}
     */
    public function createTransaction(Order $order): array
    {
        $order->loadMissing('items');

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => (string) $item->product_variant_id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => mb_substr($item->product_name.' - '.($item->variant_label ?? 'Default'), 0, 50),
            ];
        }

        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim',
            ];
        }

        if ($order->discount_total > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -(int) $order->discount_total,
                'quantity' => 1,
                'name' => 'Diskon Voucher'.($order->voucher_code ? " ({$order->voucher_code})" : ''),
            ];
        }

        $address = is_array($order->shipping_address) ? $order->shipping_address : [];

        $payload = [
            'transaction_details' => [
                'order_id' => $order->number,
                'gross_amount' => (int) $order->grand_total,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'shipping_address' => [
                    'first_name' => $address['recipient_name'] ?? $order->customer_name,
                    'phone' => $address['phone'] ?? $order->customer_phone,
                    'address' => $address['detail'] ?? '',
                    'city' => $address['city'] ?? '',
                    'postal_code' => $address['postal_code'] ?? '',
                    'country_code' => 'IDN',
                ],
            ],
            'enabled_payments' => [
                'credit_card', 'bca_va', 'bni_va', 'bri_va', 'mandiri_clickpay',
                'permata_va', 'other_va', 'gopay', 'shopeepay', 'qris',
            ],
        ];

        if ($order->expires_at) {
            $diffInMinutes = max(1, (int) now()->diffInMinutes($order->expires_at, false));
            $payload['expiry'] = [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => $diffInMinutes,
            ];
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.base64_encode($this->serverKey.':'),
        ])->post($this->getSnapUrl(), $payload);

        if (! $response->successful()) {
            Log::error('Midtrans Snap error', [
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
                'order_id' => $order->id,
            ]);

            throw new RuntimeException('Gagal membuat transaksi pembayaran: '.($response->json('error_messages.0') ?? 'Koneksi Midtrans bermasalah'));
        }

        $responseData = $response->json();
        $snapToken = (string) ($responseData['token'] ?? '');
        $redirectUrl = (string) ($responseData['redirect_url'] ?? '');

        Payment::updateOrCreate(
            [
                'order_id' => $order->id,
                'provider' => 'midtrans',
            ],
            [
                'provider_ref' => $snapToken,
                'amount' => $order->grand_total,
                'status' => PaymentStatus::Pending,
                'payment_url' => $redirectUrl,
                'expires_at' => $order->expires_at,
                'raw_payload' => $responseData,
            ]
        );

        return [
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
        ];
    }

    public function verifySignature(array $payload): bool
    {
        $signature = (string) ($payload['signature_key'] ?? '');
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');

        if ($signature === '' || $orderId === '' || $statusCode === '' || $grossAmount === '') {
            return false;
        }

        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);

        return hash_equals($expected, $signature);
    }

    /**
     * @return array{payment_status: PaymentStatus, order_status: OrderStatus, method: ?string, transaction_id: string}
     */
    public function parseStatus(array $payload): array
    {
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $paymentType = (string) ($payload['payment_type'] ?? null);
        $transactionId = (string) ($payload['transaction_id'] ?? $payload['order_id'] ?? '');

        $paymentStatus = PaymentStatus::Pending;
        $orderStatus = OrderStatus::PendingPayment;

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $paymentStatus = PaymentStatus::Pending;
                    $orderStatus = OrderStatus::PendingPayment;
                } elseif ($fraudStatus === 'accept') {
                    $paymentStatus = PaymentStatus::Paid;
                    $orderStatus = OrderStatus::Processing;
                }
                break;

            case 'settlement':
                $paymentStatus = PaymentStatus::Paid;
                $orderStatus = OrderStatus::Processing;
                break;

            case 'pending':
                $paymentStatus = PaymentStatus::Pending;
                $orderStatus = OrderStatus::PendingPayment;
                break;

            case 'deny':
                $paymentStatus = PaymentStatus::Failed;
                $orderStatus = OrderStatus::Cancelled;
                break;

            case 'expire':
                $paymentStatus = PaymentStatus::Expired;
                $orderStatus = OrderStatus::Expired;
                break;

            case 'cancel':
                $paymentStatus = PaymentStatus::Failed;
                $orderStatus = OrderStatus::Cancelled;
                break;
        }

        return [
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus,
            'method' => $paymentType,
            'transaction_id' => $transactionId,
        ];
    }
}
