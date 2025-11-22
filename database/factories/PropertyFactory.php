<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'title' => $this->faker->streetName . ' Holdings',
            'type' => $this->faker->randomElement(['residential', 'commercial']),
            'address_line' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => 'Dhaka',
            'postal_code' => $this->faker->postcode(),
            'area_sqft' => $this->faker->numberBetween(1200, 6000),
            'is_active' => true,
            'is_available_for_rent' => false,
            'rent_price' => 0,
            'assessed_value' => $this->faker->numberBetween(2000000, 6000000),
            'land_use' => $this->faker->randomElement(['residential', 'commercial']),
            'last_valuation_at' => now()->subMonths(rand(1, 12)),
            'description' => $this->faker->sentence(8),
        ];
    }
}
