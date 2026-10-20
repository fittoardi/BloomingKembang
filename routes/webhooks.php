<?php

use App\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/payment/midtrans', [PaymentWebhookController::class, 'handleMidtrans'])
    ->name('payment.midtrans');
