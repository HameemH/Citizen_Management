<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyValuationVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_valuation_snapshot(): void
    {
        $owner = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'verified',
        ]);

        $property = Property::factory()
            ->for($owner, 'owner')
            ->create([
                'assessed_value' => 5500000,
                'land_use' => 'residential',
                'last_valuation_at' => now()->subMonths(4),
            ]);

        $this->actingAs($owner)
            ->get(route('citizen.properties.show', $property))
            ->assertOk()
            ->assertSee('Valuation Snapshot')
            ->assertSee('BDT 5,500,000.00')
            ->assertSee('residential');
    }

    public function test_non_owner_sees_restricted_message(): void
    {
        $owner = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'verified',
        ]);

        $viewer = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'verified',
        ]);

        $property = Property::factory()
            ->for($owner, 'owner')
            ->create([
                'assessed_value' => 4200000,
                'land_use' => 'commercial',
                'last_valuation_at' => now()->subMonths(8),
            ]);

        $this->actingAs($viewer)
            ->get(route('citizen.properties.show', $property))
            ->assertOk()
            ->assertSee('Valuation data is restricted')
            ->assertDontSee('4,200,000.00')
            ->assertDontSee('commercial');
    }
}
