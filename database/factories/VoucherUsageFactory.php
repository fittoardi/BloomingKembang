<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VoucherUsage>
 */
class VoucherUsageFactory extends Factory
{
    protected $model = VoucherUsage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'voucher_id' => Voucher::factory(),
            'order_id' => Order::factory(),
            'user_id' => User::factory(),
            'discount_amount' => 15000,
            'created_at' => now(),
        ];
    }
}
