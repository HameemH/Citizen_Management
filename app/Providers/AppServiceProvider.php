<?php

namespace App\Providers;

use App\Services\StripeCheckoutService;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeClient::class, function () {
            $secret = config('services.stripe.secret') ?? '';

            return new StripeClient($secret);
        });

        $this->app->singleton(StripeCheckoutService::class, function ($app) {
            return new StripeCheckoutService(
                $app->make(StripeClient::class),
                config('services.stripe.currency')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
