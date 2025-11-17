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

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@citizen.gov / admin123');
    }
}
