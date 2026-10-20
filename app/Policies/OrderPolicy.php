<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin', 'warehouse_staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->hasRole(['super_admin', 'admin', 'warehouse_staff'])) {
            return true;
        }

        return $order->user_id !== null && $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        if ($order->status !== OrderStatus::PendingPayment) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $order->user_id !== null && $order->user_id === $user->id;
    }

    /**
     * Determine whether the user can pay the order.
     */
    public function pay(User $user, Order $order): bool
    {
        if ($order->status !== OrderStatus::PendingPayment) {
            return false;
        }

        if ($order->expires_at && $order->expires_at->isPast()) {
            return false;
        }

        return $order->user_id !== null && $order->user_id === $user->id;
    }
}
