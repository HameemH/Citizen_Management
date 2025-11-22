<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Notifications\PropertyTransferCompleted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PropertyTransferNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_approval_notifies_parties(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $previousOwner = User::factory()->create(['role' => 'citizen']);
        $newOwner = User::factory()->create(['role' => 'citizen']);

        $property = Property::factory()->for($previousOwner, 'owner')->create([
            'title' => 'Lakeside Residency',
        ]);

        $propertyRequest = PropertyRequest::create([
            'user_id' => $previousOwner->id,
            'property_id' => $property->id,
            'type' => 'transfer',
            'payload' => [
                'target_email' => $newOwner->email,
            ],
            'status' => 'pending',
        ]);

        Notification::fake();

        $this->actingAs($admin)
            ->post(route('admin.properties.requests.handle', $propertyRequest), [
                'action' => 'approve',
            ])
            ->assertSessionHas('status');

        $property->refresh();
        $this->assertEquals($newOwner->id, $property->owner_id);

        Notification::assertSentTo($newOwner, PropertyTransferCompleted::class);
        Notification::assertSentTo($previousOwner, PropertyTransferCompleted::class);
    }
}
