<?php

namespace App\Providers;

use App\Contracts\CarePaymentGatewayInterface;
use App\Contracts\DocumentVerificationProviderInterface;
use App\Contracts\IdentityVerificationProviderInterface;
use App\Contracts\MonetizationGatewayInterface;
use App\Contracts\PaymentGatewayInterface;
use App\Contracts\ReferralPayoutGatewayInterface;
use App\Contracts\RentPaymentGatewayInterface;
use App\Models\Setting;
use App\Services\Payment\AdminManualReferralPayoutGateway;
use App\Services\Payment\SandboxCarePaymentGateway;
use App\Services\Payment\SandboxMonetizationGateway;
use App\Services\Payment\SandboxPaymentGateway;
use App\Services\Payment\SandboxRentPaymentGateway;
use App\Services\Verification\SandboxDocumentVerificationProvider;
use App\Services\Verification\SandboxIdentityVerificationProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            SandboxPaymentGateway::class
        );

        $this->app->bind(
            IdentityVerificationProviderInterface::class,
            SandboxIdentityVerificationProvider::class
        );

        $this->app->bind(
            DocumentVerificationProviderInterface::class,
            SandboxDocumentVerificationProvider::class
        );

        $this->app->bind(
            RentPaymentGatewayInterface::class,
            SandboxRentPaymentGateway::class
        );

        $this->app->bind(
            CarePaymentGatewayInterface::class,
            SandboxCarePaymentGateway::class
        );

        $this->app->bind(
            ReferralPayoutGatewayInterface::class,
            AdminManualReferralPayoutGateway::class
        );

        $this->app->bind(
            MonetizationGatewayInterface::class,
            SandboxMonetizationGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->toArray();
                View::share('settings', $settings);
            }
        } catch (\Throwable $e) {
            // graceful fallback
        }
    }
}
