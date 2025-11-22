<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\TaxAssessment;
use App\Models\User;
use App\Services\StripeCheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\Checkout\Session as StripeSession;
use Tests\TestCase;

class CitizenStripePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_start_stripe_checkout_for_outstanding_assessment(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'verified',
        ]);

        $property = Property::factory()->create(['owner_id' => $citizen->id]);
        $assessment = TaxAssessment::factory()->issued()->create([
            'owner_id' => $citizen->id,
            'property_id' => $property->id,
        ]);

        $stripe = Mockery::mock(StripeCheckoutService::class);
        $stripe->shouldReceive('createPaymentSession')
            ->once()
            ->andReturn(StripeSession::constructFrom([
                'id' => 'cs_test',
                'url' => 'https://stripe.test/session',
            ]));

        $this->app->instance(StripeCheckoutService::class, $stripe);

        $this->actingAs($citizen)
            ->post(route('citizen.taxes.pay', $assessment))
            ->assertRedirect('https://stripe.test/session');
    }

    public function test_success_callback_records_payment_and_marks_assessment_paid(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'verified',
        ]);

        $property = Property::factory()->create(['owner_id' => $citizen->id]);
        $assessment = TaxAssessment::factory()->issued()->create([
            'owner_id' => $citizen->id,
            'property_id' => $property->id,
            'tax_amount' => 1500,
        ]);

        $stripe = Mockery::mock(StripeCheckoutService::class);
        $stripe->shouldReceive('retrieveSession')
            ->once()
            ->with('sess_123')
            ->andReturn(StripeSession::constructFrom([
                'id' => 'cs_test',
                'payment_status' => 'paid',
                'metadata' => [
                    'assessment_id' => (string) $assessment->id,
                ],
                'payment_intent' => 'pi_test',
                'amount_total' => 150000,
                'currency' => 'bdt',
            ]));

        $this->app->instance(StripeCheckoutService::class, $stripe);

        $this->actingAs($citizen)
            ->get(route('citizen.taxes.payment.success', ['session_id' => 'sess_123']))
            ->assertRedirect(route('citizen.taxes.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('tax_payments', [
            'tax_assessment_id' => $assessment->id,
            'reference' => 'pi_test',
            'method' => 'stripe_checkout',
        ]);

        $this->assertEquals('paid', $assessment->fresh()->status);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
