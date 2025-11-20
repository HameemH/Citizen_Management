<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $citizens = User::where('role', 'citizen')->take(5)->get();

        if ($citizens->isEmpty()) {
            return;
        }

        foreach ($citizens as $index => $citizen) {
            Property::create([
                'owner_id' => $citizen->id,
                'title' => 'Property #' . ($index + 1),
                'type' => $index % 2 === 0 ? 'residential' : 'commercial',
                'address_line' => rand(10, 200) . ' Example Street',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postal_code' => '120' . $index,
                'area_sqft' => 1200 + ($index * 150),
                'is_active' => true,
                'is_available_for_rent' => $index % 2 === 0,
                'rent_price' => $index % 2 === 0 ? 15000 + ($index * 1000) : null,
                'description' => 'Sample property managed via Property Management module.',
            ]);
        }
    }
}