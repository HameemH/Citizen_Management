<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\TaxPayment;
use App\Services\TaxAssessmentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TaxAssessmentSeeder extends Seeder
{
    public function __construct(private readonly TaxAssessmentService $service)
    {
    }

    public function run(): void
    {
        $properties = Property::with('owner')->active()->take(10)->get();

        if ($properties->isEmpty()) {
            return;
        }

        $currentFiscalYear = sprintf('%d-%d', now()->year, now()->year + 1);
        $previousFiscalYear = sprintf('%d-%d', now()->year - 1, now()->year);

        foreach ($properties as $index => $property) {
            if (!$property->owner) {
                continue;
            }

            $previous = $this->service->generate($property, $previousFiscalYear);
            $previous->update([
                'status' => 'paid',
                'issued_at' => Carbon::parse($previous->due_date)->subMonths(3),
                'paid_at' => Carbon::parse($previous->due_date)->subMonth(),
            ]);

            TaxPayment::firstOrCreate(
                [
                    'tax_assessment_id' => $previous->id,
                    'reference' => 'SEED-' . $previous->id,
                ],
                [
                    'payer_id' => $property->owner_id,
                    'recorded_by' => null,
                    'amount' => $previous->tax_amount,
                    'method' => 'bank_transfer',
                    'paid_at' => $previous->paid_at,
                    'notes' => 'Seeded payment record.',
                ]
            );

            $current = $this->service->generate($property, $currentFiscalYear);
            $current->update([
                'status' => 'issued',
                'issued_at' => now()->subWeek(),
                'due_date' => now()->addMonth(),
            ]);

            if ($index % 3 === 0) {
                $current->update([
                    'status' => 'overdue',
                    'due_date' => now()->subDays(15),
                ]);
            }

            if ($index % 5 === 0) {
                $draft = $this->service->generate($property, sprintf('%d-%d', now()->year + 1, now()->year + 2));
                $draft->update([
                    'status' => 'draft',
                ]);
            }
        }
    }
}
