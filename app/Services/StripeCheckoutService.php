<?php

namespace App\Services;

use App\Models\TaxAssessment;
use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripeCheckoutService
{
    public function __construct(
        private readonly StripeClient $client,
        private readonly ?string $currency = null,
    ) {
    }

    public function createPaymentSession(TaxAssessment $assessment, User $user): Session
    {
        $successUrl = $this->resolveSuccessUrl();
        $cancelUrl = $this->resolveCancelUrl();

        return $this->client->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'customer_email' => $user->email,
            'metadata' => [
                'assessment_id' => (string) $assessment->id,
                'user_id' => (string) $user->id,
            ],
            'line_items' => [[
                'price_data' => [
                    'currency' => $this->formatCurrency(),
                    'unit_amount' => $this->toStripeAmount($assessment->tax_amount),
                    'product_data' => [
                        'name' => sprintf('Property Tax %s', $assessment->fiscal_year),
                        'description' => $assessment->property?->title,
                    ],
                ],
                'quantity' => 1,
            ]],
        ]);
    }

    public function retrieveSession(string $sessionId): Session
    {
        return $this->client->checkout->sessions->retrieve($sessionId, [
            'expand' => ['payment_intent'],
        ]);
    }

    private function resolveSuccessUrl(): string
    {
        return config('services.stripe.success_url')
            ?? route('citizen.taxes.payment.success', [], false) . '?session_id={CHECKOUT_SESSION_ID}';
    }

    private function resolveCancelUrl(): string
    {
        return config('services.stripe.cancel_url')
            ?? route('citizen.taxes.index', [], false);
    }

    private function toStripeAmount(float $amount): int
    {
        return (int) round($amount * 100);
    }

    private function formatCurrency(): string
    {
        return strtolower($this->currency ?: 'bdt');
    }
}
