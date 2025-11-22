<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CitizenReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_download_own_receipt(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $property = Property::factory()->create(['owner_id' => $citizen->id]);
        $assessment = TaxAssessment::factory()->issued()->create([
            'owner_id' => $citizen->id,
            'property_id' => $property->id,
            'tax_amount' => 12000,
        ]);
        $payment = TaxPayment::factory()->create([
            'tax_assessment_id' => $assessment->id,
            'payer_id' => $citizen->id,
            'amount' => 12000,
        ]);

        $this->actingAs($citizen)
            ->get(route('citizen.taxes.payments.receipt', $payment))
            ->assertOk()
            ->assertSee('Payment Receipt')
            ->assertSee(number_format(12000, 2));
    }

    public function test_citizen_cannot_access_someone_elses_receipt(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $other = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $property = Property::factory()->create(['owner_id' => $other->id]);
        $assessment = TaxAssessment::factory()->issued()->create([
            'owner_id' => $other->id,
            'property_id' => $property->id,
        ]);
        $payment = TaxPayment::factory()->create([
            'tax_assessment_id' => $assessment->id,
            'payer_id' => $other->id,
        ]);

        $this->actingAs($citizen)
            ->get(route('citizen.taxes.payments.receipt', $payment))
            ->assertStatus(403);
    }
}
