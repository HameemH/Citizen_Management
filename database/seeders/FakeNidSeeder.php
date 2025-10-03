<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FakeNid;

class FakeNidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Generating 1000 NID records using factory...\n";
        
        // Generate 1000 NID records with different statuses:
        // - 600 clean/available NIDs
        // - 150 already verified NIDs 
        // - 200 blocked NIDs
        // - 50 clean NIDs (for testing)
        
        FakeNid::factory(600)->clean()->create();
        FakeNid::factory(150)->verified()->create();
        FakeNid::factory(200)->blocked()->create();
        FakeNid::factory(50)->clean()->create();
        
        // Get statistics
        $totalCount = FakeNid::count();
        $blockedCount = FakeNid::where('is_blocked', true)->count();
        $verifiedCount = FakeNid::where('is_verified', true)->count();
        $availableCount = FakeNid::where('is_blocked', false)->where('is_verified', false)->count();
        
        echo "=== NID Database Generated Successfully! ===\n";
        echo "Total NIDs created: {$totalCount}\n";
        echo "Available NIDs: {$availableCount}\n";
        echo "Already Verified NIDs: {$verifiedCount}\n";
        echo "Blocked NIDs: {$blockedCount}\n";
        
        // Show some sample blocked NIDs
        echo "\n=== Sample Blocked NIDs ===\n";
        $sampleBlocked = FakeNid::where('is_blocked', true)->limit(3)->get();
        foreach ($sampleBlocked as $nid) {
            echo "{$nid->nid} - {$nid->name} ({$nid->reason})\n";
        }
        
        // Show some sample verified NIDs
        echo "\n=== Sample Verified NIDs ===\n";
        $sampleVerified = FakeNid::where('is_verified', true)->limit(3)->get();
        foreach ($sampleVerified as $nid) {
            echo "{$nid->nid} - {$nid->name} (Verified on {$nid->verified_at->format('Y-m-d')})\n";
        }
        
        // Show some sample available NIDs
        echo "\n=== Sample Available NIDs ===\n";
        $sampleAvailable = FakeNid::where('is_blocked', false)->where('is_verified', false)->limit(3)->get();
        foreach ($sampleAvailable as $nid) {
            echo "{$nid->nid} - {$nid->name} (Available for verification)\n";
        }
    }
}
