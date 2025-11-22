<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyRequest;
use App\Models\RentAgreement;
use App\Models\RentalRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_approval_generates_rent_agreement(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $tenant = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $property = Property::factory()->for($owner, 'owner')->create();

        $rentalRequest = RentalRequest::create([
            'property_id' => $property->id,
            'user_id' => $tenant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.rental-requests.handle', $rentalRequest), [
                'action' => 'approve',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonths(12)->format('Y-m-d'),
                'monthly_rent' => 15000,
                'security_deposit' => 30000,
            ])
            ->assertSessionHas('status');

        $agreement = RentAgreement::first();

        $this->assertNotNull($agreement);
        $this->assertEquals($tenant->id, $agreement->tenant_id);
        $this->assertEquals($owner->id, $agreement->landlord_id);
        $this->assertEquals(15000.0, (float) $agreement->monthly_rent);
    }

    public function test_tenant_can_view_own_agreement(): void
    {
        $tenant = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $owner = User::factory()->create(['role' => 'citizen', 'verification_status' => 'verified']);
        $property = Property::factory()->for($owner, 'owner')->create();

        $agreement = RentAgreement::factory()->for($property)->create([
            'tenant_id' => $tenant->id,
            'landlord_id' => $owner->id,
        ]);

        $this->actingAs($tenant)
            ->get(route('citizen.rent-agreements.show', $agreement))
            ->assertOk()
            ->assertSee($agreement->agreement_number);
    }
}
