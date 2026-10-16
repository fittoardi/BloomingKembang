<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number', 30)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name', 120);
            $table->string('customer_email', 190);
            $table->string('customer_phone', 20);
            $table->json('shipping_address');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->string('voucher_code', 40)->nullable();
            $table->string('status', 20)->default('pending_payment');
            $table->string('payment_status', 20)->default('pending');
            $table->string('courier', 40)->nullable();
            $table->string('courier_service', 60)->nullable();
            $table->string('tracking_number', 60)->nullable();
            $table->unsignedInteger('total_weight_gram')->default(0);
            $table->text('customer_note')->nullable();
            $table->text('internal_note')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['status', 'expires_at']);
            $table->index('payment_status');
            $table->index('customer_email');
            $table->index('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
