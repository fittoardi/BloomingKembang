<?php

namespace App\Providers;

use App\Listeners\LinkGuestOrdersToUser;
use App\Listeners\MergeCartOnLogin;
use App\Services\Payment\MidtransPaymentGateway;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Shipping\ShippingProviderInterface;
use App\Services\Shipping\SimulatedShippingProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ShippingProviderInterface::class, SimulatedShippingProvider::class);
        $this->app->bind(PaymentGatewayInterface::class, MidtransPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, MergeCartOnLogin::class);
        Event::listen(Registered::class, LinkGuestOrdersToUser::class);
        Event::listen(Verified::class, LinkGuestOrdersToUser::class);

        RateLimiter::for('webhooks', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('track-order', function (Request $request) {
            return Limit::perMinute(15)->by($request->ip());
        });
    }
}
