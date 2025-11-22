<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\RentAgreement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<RentAgreement>
 */
class RentAgreementFactory extends Factory
{
    protected $model = RentAgreement::class;

    public function definition(): array
    {
        $start = now()->subMonths(rand(0, 6));
        $end = (clone $start)->addYear();

        return [
            'property_id' => Property::factory(),
            'rental_request_id' => null,
            'landlord_id' => User::factory(),
            'tenant_id' => User::factory(),
            'approved_by' => null,
            'agreement_number' => 'RA-' . strtoupper(Str::random(8)),
            'start_date' => $start,
            'end_date' => $end,
            'monthly_rent' => $this->faker->numberBetween(8000, 25000),
            'security_deposit' => $this->faker->numberBetween(10000, 50000),
            'terms_text' => $this->faker->paragraph(3),
            'status' => 'active',
            'generated_at' => now(),
        ];
    }
}
