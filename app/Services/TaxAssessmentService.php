<?php

namespace App\Services;

use App\Models\Property;
use App\Models\TaxAssessment;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class TaxAssessmentService
{
    public function generate(Property $property, string $fiscalYear, ?float $assessedValue = null): TaxAssessment
    {
        if (!$property->owner_id) {
            throw new InvalidArgumentException('Property requires an owner before generating assessments.');
        }

        $assessed = $assessedValue ?? (float) ($property->assessed_value ?? 0);
        $landUse = $property->land_use ?? $property->type ?? 'residential';
        $rate = config("tax.rates.$landUse", config('tax.default_rate'));
        $amount = round($assessed * $rate, 2);

        return TaxAssessment::updateOrCreate(
            [
                'property_id' => $property->id,
                'fiscal_year' => $fiscalYear,
            ],
            [
                'owner_id' => $property->owner_id,
                'assessed_value_snapshot' => $assessed,
                'land_use_snapshot' => $landUse,
                'tax_rate' => $rate,
                'tax_amount' => $amount,
                'status' => 'draft',
                'due_date' => $this->dueDateForYear($fiscalYear),
            ]
        );
    }

    protected function dueDateForYear(string $fiscalYear): Carbon
    {
        $year = (int) substr($fiscalYear, 0, 4);
        $month = config('tax.due_month', 6);
        $day = config('tax.due_day', 30);

        return Carbon::create($year, $month, $day);
    }
}
