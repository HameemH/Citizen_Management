<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\TaxAssessment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaxAssessment>
 */
class TaxAssessmentFactory extends Factory
{
    protected $model = TaxAssessment::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'owner_id' => null,
            'fiscal_year' => sprintf('%d-%d', now()->year, now()->year + 1),
            'assessed_value_snapshot' => $this->faker->numberBetween(2000000, 6000000),
            'land_use_snapshot' => $this->faker->randomElement(['residential', 'commercial']),
            'tax_rate' => 0.008,
            'tax_amount' => $this->faker->numberBetween(15000, 45000),
            'status' => 'draft',
            'due_date' => now()->addMonths(2),
            'issued_at' => null,
            'paid_at' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (TaxAssessment $assessment) {
            if (empty($assessment->owner_id) && $assessment->property) {
                $assessment->owner_id = $assessment->property->owner_id;
            }
        })->afterCreating(function (TaxAssessment $assessment) {
            if (empty($assessment->owner_id)) {
                $assessment->owner_id = $assessment->property?->owner_id;
                $assessment->save();
            }
        });
    }

    public function issued(): static
    {
        return $this->state(fn () => [
            'status' => 'issued',
            'issued_at' => now(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'status' => 'overdue',
            'due_date' => now()->subWeek(),
            'issued_at' => now()->subMonths(2),
        ]);
    }
}
