<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@citizen.gov',
            'role' => 'admin',
            'verification_status' => 'verified',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create Verified Citizen
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'citizen',
            'verification_status' => 'verified',
            'password' => Hash::make('citizen123'),
            'email_verified_at' => now(),
            'verification_requested_at' => now()->subDays(5),
            'verified_at' => now()->subDays(2),
        ]);

        // Create Pending Citizen
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'role' => 'citizen',
            'verification_status' => 'pending',
            'password' => Hash::make('citizen123'),
            'email_verified_at' => now(),
            'verification_requested_at' => now()->subDay(),
        ]);

        // Create Rejected Citizen
        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'role' => 'citizen',
            'verification_status' => 'rejected',
            'password' => Hash::make('citizen123'),
            'email_verified_at' => now(),
            'verification_requested_at' => now()->subDays(3),
            'rejected_at' => now()->subDay(),
        ]);

        // Create Additional Citizens for Testing
        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'role' => 'citizen',
            'verification_status' => 'pending',
            'password' => Hash::make('citizen123'),
            'email_verified_at' => now(),
            'verification_requested_at' => now()->subHours(6),
        ]);

        User::create([
            'name' => 'Michael Brown',
            'email' => 'michael@example.com',
            'role' => 'citizen',
            'verification_status' => 'verified',
            'password' => Hash::make('citizen123'),
            'email_verified_at' => now(),
            'verification_requested_at' => now()->subDays(10),
            'verified_at' => now()->subDays(7),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('=== Test Credentials ===');
        $this->command->info('Admin: admin@citizen.gov / admin123');
        $this->command->info('Verified Citizen: john@example.com / citizen123');
        $this->command->info('Pending Citizen: jane@example.com / citizen123');
        $this->command->info('Rejected Citizen: bob@example.com / citizen123');
    }
}
