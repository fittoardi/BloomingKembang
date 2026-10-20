<?php

namespace App\Listeners;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class LinkGuestOrdersToUser
{
    public function handle(Registered|Verified $event): void
    {
        if ($event->user instanceof User && $event->user->email) {
            Order::whereNull('user_id')
                ->where('customer_email', $event->user->email)
                ->update(['user_id' => $event->user->id]);
        }
    }
}
