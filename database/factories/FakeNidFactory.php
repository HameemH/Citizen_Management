<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FakeNid>
 */
class FakeNidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common Bangladeshi names
        $maleNames = ['Abdullah', 'Mohammad', 'Ahmed', 'Rahman', 'Hassan', 'Hussain', 'Ali', 'Khan', 'Islam', 'Ullah', 'Karim', 'Mahmud', 'Hasan', 'Reza', 'Shahid'];
        $femaleNames = ['Fatima', 'Ayesha', 'Rashida', 'Nasreen', 'Salma', 'Rehana', 'Hasina', 'Rokeya', 'Sultana', 'Begum', 'Khatun', 'Parvin', 'Ruma', 'Lima', 'Sadia'];
        
        // Common father/mother name patterns
        $fatherPrefixes = ['Md.', 'Mr.', 'Moulvi', 'Haji'];
        $motherPrefixes = ['Mrs.', 'Mst.', 'Begum'];
        
        // Bangladeshi districts and places
        $districts = ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barisal', 'Rangpur', 'Mymensingh', 'Comilla', 'Narayanganj', 'Gazipur', 'Bogra', 'Jessore'];
        
        // Generate random 10-digit NID
        $nid = $this->faker->unique()->numerify('##########');
        
        // Random gender
        $gender = $this->faker->randomElement(['Male', 'Female']);
        $names = $gender === 'Male' ? $maleNames : $femaleNames;
        
        // Generate name
        $firstName = $this->faker->randomElement($names);
        $lastName = $this->faker->randomElement(['Rahman', 'Khan', 'Ahmed', 'Hassan', 'Ali', 'Islam', 'Uddin', 'Haque', 'Miah', 'Sheikh']);
        $fullName = $firstName . ' ' . $lastName;
        
        // Generate father's name
        $fatherName = $this->faker->randomElement($fatherPrefixes) . ' ' . $this->faker->randomElement($maleNames) . ' ' . $this->faker->randomElement(['Rahman', 'Khan', 'Ahmed', 'Hassan', 'Ali']);
        
        // Generate mother's name
        $motherName = $this->faker->randomElement($motherPrefixes) . ' ' . $this->faker->randomElement($femaleNames) . ' ' . $this->faker->randomElement(['Begum', 'Khatun', 'Sultana']);
        
        // Random birth place
        $birthPlace = $this->faker->randomElement($districts);
        
        // Generate addresses
        $village = 'Village-' . $this->faker->randomNumber(2);
        $upazila = $this->faker->randomElement(['Sadar', 'Dhamrai', 'Keraniganj', 'Nawabganj', 'Dohar']);
        $district = $this->faker->randomElement($districts);
        
        $presentAddress = "House-{$this->faker->randomNumber(2)}, Road-{$this->faker->randomNumber(1)}, {$village}, {$upazila}, {$district}";
        $permanentAddress = $this->faker->boolean(70) ? $presentAddress : "House-{$this->faker->randomNumber(2)}, {$village}, {$upazila}, {$district}";
        
        // Reasons for blocking (only some will be blocked)
        $blockReasons = [
            'Fraudulent activities detected',
            'Identity theft reported',
            'Multiple registrations with same person',
            'Fake documents submitted',
            'Criminal background verification failed',
            'Age verification mismatch',
            'Address verification failed',
            'Deceased person record',
            'Duplicate NID found',
            'Suspicious verification pattern'
        ];
        
        // 20% chance of being blocked
        $isBlocked = $this->faker->boolean(20);
        
        return [
            'nid' => $nid,
            'name' => $fullName,
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'date_of_birth' => $this->faker->date('Y-m-d', '2005-12-31'), // Born before 2006
            'gender' => $gender,
            'birth_place' => $birthPlace,
            'present_address' => $presentAddress,
            'permanent_address' => $permanentAddress,
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'reason' => $isBlocked ? $this->faker->randomElement($blockReasons) : null,
            'is_blocked' => $isBlocked,
            'is_verified' => false, // Default to not verified
            'verified_at' => null,
            'verified_by' => null,
        ];
    }
    
    /**
     * Create a blocked NID entry
     */
    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_blocked' => true,
            'reason' => $this->faker->randomElement([
                'Fraudulent activities detected',
                'Identity theft reported',
                'Multiple registrations with same person',
                'Fake documents submitted',
                'Criminal background verification failed'
            ]),
        ]);
    }
    
    /**
     * Create a clean NID entry
     */
    public function clean(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_blocked' => false,
            'reason' => null,
            'is_verified' => false,
        ]);
    }

    /**
     * Create a verified NID entry
     */
    public function verified(): static
    {
        return $this->state(function () {
            static $verifierId;

            if (!$verifierId) {
                $verifierId = User::where('role', 'admin')->value('id')
                    ?? User::orderBy('id')->value('id');
            }

            return [
                'is_blocked' => false,
                'reason' => null,
                'is_verified' => true,
                'verified_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'verified_by' => $verifierId,
            ];
        });
    }
}
