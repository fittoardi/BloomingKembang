<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway,
    ) {}

    public function handleMidtrans(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans webhook received', ['payload' => $payload]);

        if (! $this->paymentGateway->verifySignature($payload)) {
            Log::warning('Midtrans webhook invalid signature', ['payload' => $payload]);

            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $orderNumber = $payload['order_id'] ?? null;
        if (! $orderNumber) {
            return response()->json(['message' => 'Missing order_id.'], 400);
        }

        $order = Order::with('items')->where('number', $orderNumber)->first();
        if (! $order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        $parsed = $this->paymentGateway->parseStatus($payload);
        $transactionId = (string) ($payload['transaction_id'] ?? $order->number);
        $method = $parsed['method'] ?? ($payload['payment_type'] ?? 'midtrans');

        // Idempotency: Jika pesanan sudah lunas, jangan proses ulang
        if ($order->payment_status === PaymentStatus::Paid) {
            return response()->json([
                'message' => 'Order already processed (idempotent).',
                'order_number' => $order->number,
            ], 200);
        }

        if ($parsed['payment_status'] === PaymentStatus::Paid) {
            DB::transaction(function () use ($order, $transactionId, $method, $payload, &$payment) {
                $order->update([
                    'payment_status' => PaymentStatus::Paid,
                    'status' => OrderStatus::Processing,
                    'paid_at' => now(),
                ]);

                $payment = Payment::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'provider' => 'midtrans',
                    ],
                    [
                        'provider_ref' => $transactionId,
                        'method' => $method,
                        'amount' => $order->grand_total,
                        'status' => PaymentStatus::Paid,
                        'paid_at' => now(),
                        'raw_payload' => $payload,
                    ]
                );
            });

            // Trigger event OrderPaid untuk finalisasi stok, mutasi sale, dan kirim notifikasi
            event(new OrderPaid($order, $payment));

            return response()->json([
                'message' => 'Payment settled successfully.',
                'order_number' => $order->number,
            ]);
        }

        if (in_array($parsed['payment_status'], [PaymentStatus::Failed, PaymentStatus::Expired])) {
            DB::transaction(function () use ($order, $parsed, $transactionId, $method, $payload): void {
                $oldStatus = $order->status;

                $order->update([
                    'payment_status' => $parsed['payment_status'],
                    'status' => $parsed['order_status'],
                    'cancelled_at' => now(),
                    'cancel_reason' => 'Pembayaran '.($parsed['payment_status']->label()),
                ]);

                Payment::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'provider' => 'midtrans',
                    ],
                    [
                        'provider_ref' => $transactionId,
                        'method' => $method,
                        'amount' => $order->grand_total,
                        'status' => $parsed['payment_status'],
                        'raw_payload' => $payload,
                    ]
                );

                // Lepas reservasi stok jika sebelumnya masih pending
                if ($oldStatus === OrderStatus::PendingPayment) {
                    foreach ($order->items as $item) {
                        $variant = ProductVariant::where('id', $item->product_variant_id)
                            ->lockForUpdate()
                            ->first();

                        if (! $variant) {
                            continue;
                        }

                        $variant->decrement('reserved', $item->quantity);

                        StockMovement::create([
                            'product_variant_id' => $variant->id,
                            'type' => 'release',
                            'quantity' => $item->quantity,
                            'stock_after' => $variant->stock,
                            'order_id' => $order->id,
                            'note' => "Pembatalan reservasi stok webhook pesanan #{$order->number}",
                        ]);
                    }
                }

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => $oldStatus->value,
                    'to_status' => $parsed['order_status']->value,
                    'note' => 'Status diperbarui via webhook Midtrans: '.($payload['transaction_status'] ?? 'unknown'),
                ]);
            });

            return response()->json([
                'message' => 'Payment marked as '.$parsed['payment_status']->value,
                'order_number' => $order->number,
            ]);
        }

        return response()->json([
            'message' => 'Webhook received with pending status.',
            'order_number' => $order->number,
        ]);
    }
}
