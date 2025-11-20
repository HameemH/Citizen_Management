<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_submit_property_add_request(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($citizen)
            ->post(route('citizen.properties.request.add.store'), [
                'title' => 'Test Property',
                'type' => 'residential',
                'address_line' => '123 Example Street',
                'city' => 'Dhaka',
            ]);

        $response->assertRedirect(route('citizen.properties.index'));
        $this->assertDatabaseHas('property_requests', [
            'user_id' => $citizen->id,
            'type' => 'add',
            'status' => 'pending',
        ]);
    }

    public function test_unverified_citizen_is_redirected_from_property_pages(): void
    {
        $citizen = User::factory()->create([
            'role' => 'citizen',
            'verification_status' => 'pending',
        ]);

        $this->actingAs($citizen)
            ->get(route('citizen.properties.index'))
            ->assertRedirect(route('citizen.dashboard'));
    }

    public function test_admin_can_create_property_directly(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.properties.store'), [
                'title' => 'Managed Property',
                'type' => 'commercial',
            ]);

        $response->assertRedirect(route('admin.properties.index'));
        $this->assertDatabaseHas('properties', [
            'title' => 'Managed Property',
            'type' => 'commercial',
        ]);
    }
}