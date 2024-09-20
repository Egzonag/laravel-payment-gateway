<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\EloquentPaymentRepository;
use App\Payments\PaymentGatewayInterface;
use App\Payments\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interface to Eloquent implementation
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);

        // Bind payment gateway to Stripe implementation
        $this->app->bind(PaymentGatewayInterface::class, StripePaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
