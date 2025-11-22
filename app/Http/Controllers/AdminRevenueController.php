<?php

namespace App\Http\Controllers;

use App\Models\TaxAssessment;
use App\Services\RevenueDashboardService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminRevenueController extends Controller
{
    public function __construct(private readonly RevenueDashboardService $revenue)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'fiscal_year', 'city', 'q']);

        $assessmentQuery = $this->filteredAssessmentsQuery($filters);
        $reconciliationRows = (clone $assessmentQuery)->paginate(15)->withQueryString();

        $kpis = $this->revenue->getKpis();
        $trend = $this->revenue->getMonthlyTrend(6);
        $delinquents = $this->revenue->getTopDelinquents();
        $agingBuckets = $this->revenue->getAgingBuckets();
        $overdueCount = $this->revenue->getOverdueCount();
        $upcomingValuations = $this->revenue->getUpcomingValuations();
        $valuationThresholdDays = config('tax.valuation_upcoming_threshold_days', 60);

        $availableYears = TaxAssessment::select('fiscal_year')
            ->distinct()
            ->pluck('fiscal_year')
            ->filter()
            ->sortDesc();

        return view('admin.revenue.index', compact(
            'filters',
            'reconciliationRows',
            'kpis',
            'trend',
            'delinquents',
            'agingBuckets',
            'overdueCount',
            'availableYears',
            'upcomingValuations',
            'valuationThresholdDays'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->only(['status', 'fiscal_year', 'city', 'q']);
        $filename = 'tax-reconciliation-' . now()->format('Ymd_His') . '.csv';
        $query = $this->filteredAssessmentsQuery($filters);

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Property', 'Owner', 'Fiscal Year', 'Status', 'Assessed Amount', 'Paid Amount', 'Outstanding', 'Due Date', 'City',
            ]);

            $query->chunk(200, function ($chunk) use ($handle) {
                foreach ($chunk as $assessment) {
                    $paid = (float) ($assessment->payments_sum_amount ?? 0);
                    $outstanding = max((float) $assessment->tax_amount - $paid, 0);

                    fputcsv($handle, [
                        $assessment->property?->title,
                        $assessment->owner?->display_name,
                        $assessment->fiscal_year,
                        ucfirst($assessment->status),
                        number_format($assessment->tax_amount, 2),
                        number_format($paid, 2),
                        number_format($outstanding, 2),
                        optional($assessment->due_date)->format('Y-m-d'),
                        $assessment->property?->city,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportValuations(Request $request): StreamedResponse
    {
        $withinDays = (int) $request->integer('within_days', config('tax.valuation_upcoming_threshold_days', 60));
        $filename = 'upcoming-valuations-' . now()->format('Ymd_His') . '.csv';
        $rows = $this->revenue->getUpcomingValuations($withinDays, null);

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Property', 'Owner', 'City', 'Last Valuation', 'Next Due', 'Days Until Due']);

            $rows->each(function ($row) use ($handle) {
                fputcsv($handle, [
                    $row['property']->title,
                    $row['owner']?->display_name,
                    $row['property']->city,
                    optional($row['last_valuation_at'])->format('Y-m-d'),
                    optional($row['next_due'])->format('Y-m-d'),
                    $row['days_until'],
                ]);
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function filteredAssessmentsQuery(array $filters)
    {
        return TaxAssessment::with(['owner', 'property'])
            ->withSum('payments as payments_sum_amount', 'amount')
            ->when(!empty($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(!empty($filters['fiscal_year']), fn ($query) => $query->where('fiscal_year', $filters['fiscal_year']))
            ->when(!empty($filters['city']), fn ($query) => $query->whereHas('property', fn ($q) => $q->where('city', 'like', '%' . $filters['city'] . '%')))
            ->when(!empty($filters['q']), function ($query) use ($filters) {
                $query->where(function ($sub) use ($filters) {
                    $sub->whereHas('property', function ($propertyQuery) use ($filters) {
                        $propertyQuery->where('title', 'like', '%' . $filters['q'] . '%')
                            ->orWhere('city', 'like', '%' . $filters['q'] . '%');
                    })->orWhereHas('owner', function ($ownerQuery) use ($filters) {
                        $ownerQuery->where('name', 'like', '%' . $filters['q'] . '%')
                            ->orWhere('full_name', 'like', '%' . $filters['q'] . '%');
                    });
                });
            })
            ->latest('due_date');
    }
}
