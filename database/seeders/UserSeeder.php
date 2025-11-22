<?php

namespace Database\Seeders;

use App\Models\FakeNid;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@citizen.gov'],
            [
                'name' => 'Admin User',
                'role' => 'admin',
                'verification_status' => 'verified',
                'password' => Hash::make('admin123'),
            ]
        );

        $admin->forceFill(['email_verified_at' => now(), 'verified_at' => now(), 'verified_by' => $admin->id])->save();

        $citizenCredentials = [
            ['email' => 'test@gmail.com', 'phone' => '01700000001'],
            ['email' => 'test1@gmail.com', 'phone' => '01700000002'],
        ];

        $required = count($citizenCredentials);
        $availableNids = FakeNid::query()
            ->where('is_blocked', false)
            ->where('is_verified', false)
            ->take($required)
            ->get();

        if ($availableNids->count() < $required) {
            $this->command?->warn('Not enough clean Fake NID records to seed citizens. Run FakeNidSeeder first.');
            return;
        }

        foreach ($citizenCredentials as $index => $credentials) {
            $nidRecord = $availableNids[$index];

            $citizen = User::updateOrCreate(
                ['email' => $credentials['email']],
                [
                    'name' => $nidRecord->name,
                    'full_name' => $nidRecord->name,
                    'role' => 'citizen',
                    'verification_status' => 'verified',
                    'password' => Hash::make('12345678'),
                    'nid_number' => $nidRecord->nid,
                    'date_of_birth' => $nidRecord->date_of_birth,
                    'father_name' => $nidRecord->father_name,
                    'mother_name' => $nidRecord->mother_name,
                    'permanent_address' => $nidRecord->permanent_address,
                    'present_address' => $nidRecord->present_address,
                    'phone_number' => $credentials['phone'],
                ]
            );

            $citizen->forceFill([
                'email_verified_at' => now(),
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ])->save();

            $nidRecord->update([
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => $admin->id,
                'is_blocked' => false,
            ]);
        }

        $this->command?->info('Users seeded successfully!');
        $this->command?->info('Admin: admin@citizen.gov / admin123');
        $this->command?->info('Citizen 1: test@gmail.com / 12345678');
        $this->command?->info('Citizen 2: test1@gmail.com / 12345678');
    }
}
