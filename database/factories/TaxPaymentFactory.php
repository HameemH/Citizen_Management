<?php

namespace Database\Factories;

use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaxPayment>
 */
class TaxPaymentFactory extends Factory
{
    protected $model = TaxPayment::class;

    public function definition(): array
    {
        return [
            'tax_assessment_id' => TaxAssessment::factory(),
            'payer_id' => User::factory(),
            'recorded_by' => null,
            'amount' => $this->faker->randomFloat(2, 5000, 50000),
            'method' => 'stripe_checkout',
            'reference' => 'FAKE-' . $this->faker->bothify('####'),
            'paid_at' => now(),
            'notes' => $this->faker->sentence(4),
        ];
    }
}
