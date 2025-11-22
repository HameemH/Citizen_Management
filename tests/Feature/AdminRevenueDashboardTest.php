<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRevenueDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_revenue_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'citizen']);
        $property = Property::factory()->create(['owner_id' => $owner->id]);

        $assessment = TaxAssessment::factory()->issued()->create([
            'owner_id' => $owner->id,
            'property_id' => $property->id,
            'tax_amount' => 25000,
        ]);

        TaxPayment::factory()->create([
            'tax_assessment_id' => $assessment->id,
            'payer_id' => $owner->id,
            'amount' => 10000,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.revenue.index'))
            ->assertOk()
            ->assertSee('Revenue & Compliance')
            ->assertSee('Download CSV');
    }

    public function test_csv_export_includes_filtered_rows(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'citizen']);
        $property = Property::factory()->create(['owner_id' => $owner->id, 'city' => 'Dhaka']);

        $assessment = TaxAssessment::factory()->issued()->create([
            'owner_id' => $owner->id,
            'property_id' => $property->id,
            'fiscal_year' => '2025-2026',
            'tax_amount' => 15000,
            'status' => 'overdue',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.revenue.export', ['fiscal_year' => '2025-2026']))
            ->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString('Property,Owner,"Fiscal Year"', $content);
        $this->assertStringContainsString($property->title, $content);
    }

    public function test_citizen_cannot_access_revenue_dashboard(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);

        $this->actingAs($citizen)
            ->get(route('admin.revenue.index'))
            ->assertRedirect(route('citizen.dashboard'));
    }

    public function test_upcoming_valuations_render_for_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'citizen']);
        Property::factory()->for($owner, 'owner')->create([
            'last_valuation_at' => Carbon::now()->subDays(420),
            'assessed_value' => 3000000,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.revenue.index'))
            ->assertOk()
            ->assertSee('Upcoming Valuations')
            ->assertSee($owner->display_name);
    }

    public function test_valuation_export_contains_due_properties(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'citizen']);
        $property = Property::factory()->for($owner, 'owner')->create([
            'title' => 'Sunrise Residency',
            'last_valuation_at' => Carbon::now()->subDays(500),
            'assessed_value' => 4100000,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.revenue.export-valuations'))
            ->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString('Sunrise Residency', $content);
        $this->assertStringContainsString($owner->display_name, $content);
    }
}
