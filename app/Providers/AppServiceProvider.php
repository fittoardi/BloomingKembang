<?php

namespace App\Providers;

use App\Listeners\LinkGuestOrdersToUser;
use App\Listeners\MergeCartOnLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

use App\Services\Shipping\ShippingProviderInterface;
use App\Services\Shipping\SimulatedShippingProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ShippingProviderInterface::class, SimulatedShippingProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, MergeCartOnLogin::class);
        Event::listen(Registered::class, LinkGuestOrdersToUser::class);
        Event::listen(Verified::class, LinkGuestOrdersToUser::class);
    }
}
