<?php

namespace Tests\Feature;

use App\Models\TaxAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTaxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_issue_assessment_and_record_payment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'citizen']);
        $assessment = TaxAssessment::factory()
            ->issued()
            ->create([
                'owner_id' => $owner->id,
            ]);

        $this->actingAs($admin)
            ->post(route('admin.taxes.payments.store', $assessment), [
                'amount' => $assessment->tax_amount,
                'method' => 'bank_transfer',
                'reference' => 'TXN123',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tax_payments', [
            'tax_assessment_id' => $assessment->id,
            'reference' => 'TXN123',
            'method' => 'bank_transfer',
        ]);

        $this->assertEquals('paid', $assessment->fresh()->status);
    }

    public function test_non_admin_cannot_access_admin_tax_routes(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);
        $assessment = TaxAssessment::factory()->create();

        $this->actingAs($citizen)
            ->post(route('admin.taxes.payments.store', $assessment), [
                'amount' => 1000,
            ])
            ->assertRedirect(route('citizen.dashboard'));

        $this->assertDatabaseMissing('tax_payments', [
            'tax_assessment_id' => $assessment->id,
        ]);
    }
}
