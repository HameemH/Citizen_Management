<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use App\Models\User;
use App\Notifications\TaxAssessmentOverdue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FlagOverdueAssessmentsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_marks_overdue_and_notifies_owner(): void
    {
        Notification::fake();

        $owner = User::factory()->create(['role' => 'citizen']);
        $property = Property::factory()->for($owner, 'owner')->create();

        $assessment = TaxAssessment::factory()->create([
            'property_id' => $property->id,
            'owner_id' => $owner->id,
            'status' => 'issued',
            'due_date' => now()->subDays(5),
            'tax_amount' => 10000,
        ]);

        TaxPayment::factory()->create([
            'tax_assessment_id' => $assessment->id,
            'payer_id' => $owner->id,
            'amount' => 2500,
            'paid_at' => now()->subDays(6),
        ]);

        $this->artisan('tax:flag-overdue')
            ->expectsOutput('Flagged 1 assessments.')
            ->assertExitCode(0);

        $assessment->refresh();
        $this->assertEquals('overdue', $assessment->status);

        Notification::assertSentTo($owner, TaxAssessmentOverdue::class);
    }
}
