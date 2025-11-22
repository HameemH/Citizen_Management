<?php

namespace App\Services;

use App\Models\Property;
use App\Models\TaxAssessment;
use App\Models\TaxPayment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class RevenueDashboardService
{
    public function getKpis(?Carbon $start = null, ?Carbon $end = null): array
    {
        $start ??= Carbon::now()->startOfYear();
        $end ??= Carbon::now();

        $issuedTotal = (float) TaxAssessment::whereBetween('issued_at', [$start, $end])->sum('tax_amount');
        $collectedTotal = (float) TaxPayment::whereBetween('paid_at', [$start, $end])->sum('amount');
        $outstandingTotal = $this->calculateOutstandingTotal();

        $collectionRate = $issuedTotal > 0
            ? round(($collectedTotal / $issuedTotal) * 100, 1)
            : 0.0;

        $avgDaysToPay = $this->calculateAverageDaysToPay($start, $end);

        return [
            'issued_total' => $issuedTotal,
            'collected_total' => $collectedTotal,
            'outstanding_total' => $outstandingTotal,
            'collection_rate' => $collectionRate,
            'avg_days_to_pay' => $avgDaysToPay,
        ];
    }

    public function getMonthlyTrend(int $months = 6): Collection
    {
        $periodEnd = Carbon::now()->endOfMonth();
        $periodStart = (clone $periodEnd)->subMonths($months - 1)->startOfMonth();

        $issued = TaxAssessment::whereBetween('issued_at', [$periodStart, $periodEnd])
            ->get(['tax_amount', 'issued_at'])
            ->groupBy(fn ($assessment) => Carbon::parse($assessment->issued_at)->format('Y-m'))
            ->map(fn ($group) => (float) $group->sum('tax_amount'));

        $collected = TaxPayment::whereBetween('paid_at', [$periodStart, $periodEnd])
            ->get(['amount', 'paid_at'])
            ->groupBy(fn ($payment) => Carbon::parse($payment->paid_at)->format('Y-m'))
            ->map(fn ($group) => (float) $group->sum('amount'));

        return collect(CarbonPeriod::create($periodStart, '1 month', $periodEnd))
            ->map(function (Carbon $date) use ($issued, $collected) {
                $key = $date->format('Y-m');

                return [
                    'label' => $date->format('M Y'),
                    'issued' => $issued->get($key, 0.0),
                    'collected' => $collected->get($key, 0.0),
                ];
            });
    }

    public function getTopDelinquents(int $limit = 5): Collection
    {
        $assessments = TaxAssessment::with(['owner', 'payments'])
            ->whereIn('status', ['issued', 'overdue'])
            ->get();

        return $assessments
            ->groupBy('owner_id')
            ->map(function (Collection $items) {
                $owner = $items->first()->owner;
                $totalAssessed = $items->sum('tax_amount');
                $totalPaid = $items->flatMap->payments->sum('amount');
                $outstanding = max($totalAssessed - $totalPaid, 0);

                return [
                    'owner' => $owner,
                    'property_count' => $items->count(),
                    'outstanding' => $outstanding,
                ];
            })
            ->filter(fn ($row) => $row['outstanding'] > 0)
            ->sortByDesc('outstanding')
            ->take($limit)
            ->values();
    }

    public function getAgingBuckets(): array
    {
        $buckets = [
            'current' => ['label' => 'Current (< Due)', 'count' => 0, 'amount' => 0.0],
            'bucket_30' => ['label' => '1-30 Days', 'count' => 0, 'amount' => 0.0],
            'bucket_60' => ['label' => '31-60 Days', 'count' => 0, 'amount' => 0.0],
            'bucket_90' => ['label' => '61-90 Days', 'count' => 0, 'amount' => 0.0],
            'bucket_120' => ['label' => '90+ Days', 'count' => 0, 'amount' => 0.0],
        ];

        $today = Carbon::today();

        TaxAssessment::with('payments')
            ->whereIn('status', ['issued', 'overdue'])
            ->get()
            ->each(function (TaxAssessment $assessment) use (&$buckets, $today) {
                $dueDate = $assessment->due_date ?? $today;
                $daysPastDue = $dueDate->diffInDays($today, false);
                $outstanding = max((float) $assessment->tax_amount - (float) $assessment->payments->sum('amount'), 0);

                if ($outstanding <= 0) {
                    return;
                }

                $bucketKey = match (true) {
                    $daysPastDue <= 0 => 'current',
                    $daysPastDue <= 30 => 'bucket_30',
                    $daysPastDue <= 60 => 'bucket_60',
                    $daysPastDue <= 90 => 'bucket_90',
                    default => 'bucket_120',
                };

                $buckets[$bucketKey]['count']++;
                $buckets[$bucketKey]['amount'] += $outstanding;
            });

        return $buckets;
    }

    public function getOverdueCount(): int
    {
        return TaxAssessment::where('status', 'overdue')->count();
    }

    public function getUpcomingValuations(?int $withinDays = null, ?int $limit = 10): Collection
    {
        $cycleDays = (int) config('tax.valuation_cycle_days', 365);
        $threshold = $withinDays ?? (int) config('tax.valuation_upcoming_threshold_days', 60);
        $today = Carbon::today();

        return Property::with('owner')
            ->whereNotNull('owner_id')
            ->get()
            ->map(function (Property $property) use ($cycleDays, $today) {
                $lastValuation = $property->last_valuation_at ?? $property->created_at ?? $today;
                $nextDue = Carbon::parse($lastValuation)->addDays($cycleDays);
                $daysUntil = $today->diffInDays($nextDue, false);

                return [
                    'property' => $property,
                    'owner' => $property->owner,
                    'last_valuation_at' => $property->last_valuation_at,
                    'next_due' => $nextDue,
                    'days_until' => $daysUntil,
                    'is_overdue' => $daysUntil < 0,
                ];
            })
            ->filter(fn ($row) => $row['days_until'] <= $threshold)
            ->sortBy('days_until')
            ->when($limit, fn ($collection) => $collection->take($limit))
            ->values();
    }

    protected function calculateOutstandingTotal(): float
    {
        return TaxAssessment::with('payments')
            ->whereIn('status', ['issued', 'overdue'])
            ->get()
            ->sum(function (TaxAssessment $assessment) {
                $paid = $assessment->payments->sum('amount');

                return max((float) $assessment->tax_amount - (float) $paid, 0);
            });
    }

    protected function calculateAverageDaysToPay(Carbon $start, Carbon $end): float
    {
        $payments = TaxPayment::with('assessment')
            ->whereBetween('paid_at', [$start, $end])
            ->whereNotNull('paid_at')
            ->get();

        if ($payments->isEmpty()) {
            return 0.0;
        }

        $average = $payments->avg(function (TaxPayment $payment) {
            $issuedAt = $payment->assessment?->issued_at;
            if (!$issuedAt) {
                return null;
            }

            return Carbon::parse($issuedAt)->diffInDays(Carbon::parse($payment->paid_at));
        });

        return round((float) $average, 1);
    }
}
