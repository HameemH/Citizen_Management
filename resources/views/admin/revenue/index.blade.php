@extends('layouts.admin')

@section('title', 'Revenue Dashboard')
@section('page-title', 'Revenue & Compliance')

@section('content')
<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Issued YTD</p>
            <p class="text-2xl font-bold text-gray-900">BDT {{ number_format($kpis['issued_total'], 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Collected YTD</p>
            <p class="text-2xl font-bold text-green-600">BDT {{ number_format($kpis['collected_total'], 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Outstanding</p>
            <p class="text-2xl font-bold text-red-600">BDT {{ number_format($kpis['outstanding_total'], 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm text-gray-500">Collection Rate</p>
            <p class="text-2xl font-bold text-indigo-700">{{ number_format($kpis['collection_rate'], 1) }}%</p>
            <p class="text-xs text-gray-500">Avg days to pay: {{ $kpis['avg_days_to_pay'] }} days</p>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Issued vs Collected (last 6 months)</h3>
            </div>
            <div class="space-y-3">
                @foreach($trend as $point)
                    <div>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>{{ $point['label'] }}</span>
                            <span>Issued: BDT {{ number_format($point['issued'], 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Collected: BDT {{ number_format($point['collected'], 0) }}</span>
                            <span class="font-semibold {{ $point['collected'] >= $point['issued'] ? 'text-green-600' : 'text-red-600' }}">
                                {{ $point['issued'] > 0 ? number_format(($point['collected'] / max($point['issued'], 1)) * 100, 0) : 0 }}%
                            </span>
                        </div>
                        <div class="mt-1 h-2 bg-gray-100 rounded-full">
                            <div class="h-2 rounded-full bg-indigo-500" style="width: {{ min(100, $point['issued'] > 0 ? ($point['collected'] / max($point['issued'], 1)) * 100 : ($point['collected'] > 0 ? 100 : 0)) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aging Buckets</h3>
            <div class="space-y-3">
                @foreach($agingBuckets as $bucket)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $bucket['label'] }}</p>
                            <p class="text-xs text-gray-500">{{ $bucket['count'] }} accounts</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">BDT {{ number_format($bucket['amount'], 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Delinquent Owners</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 uppercase text-xs">
                            <th class="py-2">Owner</th>
                            <th class="py-2">Properties</th>
                            <th class="py-2">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($delinquents as $row)
                            <tr>
                                <td class="py-2 text-gray-800">{{ $row['owner']?->display_name ?? 'N/A' }}</td>
                                <td class="py-2 text-gray-600">{{ $row['property_count'] }}</td>
                                <td class="py-2 font-semibold text-red-600">BDT {{ number_format($row['outstanding'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">No delinquent records 🎉</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Alerts</h3>
            <p class="text-sm text-gray-600">{{ $overdueCount }} assessments are currently overdue. Consider reaching out or sending reminders.</p>
            <a href="{{ route('admin.taxes.index', ['status' => 'overdue']) }}" class="inline-flex items-center mt-4 px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded shadow hover:bg-red-700">Review overdue list</a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Valuations (next {{ $valuationThresholdDays }} days)</h3>
                <p class="text-sm text-gray-500">Monitor properties whose valuation cycle is about to lapse.</p>
            </div>
            <a href="{{ route('admin.revenue.export-valuations', ['within_days' => $valuationThresholdDays]) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">Export list</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 uppercase text-xs">
                        <th class="py-2">Property</th>
                        <th class="py-2">Owner</th>
                        <th class="py-2">City</th>
                        <th class="py-2">Last Valuation</th>
                        <th class="py-2">Next Due</th>
                        <th class="py-2 text-right">Days Remaining</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($upcomingValuations as $row)
                        <tr>
                            <td class="py-2 text-gray-900">{{ $row['property']->title }}</td>
                            <td class="py-2 text-gray-600">{{ $row['owner']?->display_name ?? 'N/A' }}</td>
                            <td class="py-2 text-gray-600">{{ $row['property']->city }}</td>
                            <td class="py-2 text-gray-500">{{ optional($row['last_valuation_at'])->format('M d, Y') ?? '—' }}</td>
                            <td class="py-2 text-gray-500">{{ optional($row['next_due'])->format('M d, Y') ?? '—' }}</td>
                            <td class="py-2 text-right font-semibold {{ $row['is_overdue'] ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $row['days_until'] < 0 ? ($row['days_until'] * -1) . ' days overdue' : $row['days_until'] . ' days' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">All valuations are up to date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-4 border-b border-gray-200 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex flex-wrap items-center gap-2 text-sm">
                <select name="status" class="border rounded px-2 py-1">
                    <option value="">All statuses</option>
                    @foreach(['draft' => 'Draft', 'issued' => 'Issued', 'overdue' => 'Overdue', 'paid' => 'Paid'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)> {{ $label }} </option>
                    @endforeach
                </select>
                <select name="fiscal_year" class="border rounded px-2 py-1">
                    <option value="">All years</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" @selected(($filters['fiscal_year'] ?? '') === $year)>{{ $year }}</option>
                    @endforeach
                </select>
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="City" class="border rounded px-2 py-1" />
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search property/owner" class="border rounded px-2 py-1" />
                <button class="bg-indigo-600 text-white px-3 py-1 rounded">Filter</button>
            </form>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.revenue.export', request()->query()) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">Download CSV</a>
                <a href="{{ route('admin.revenue.export-valuations', ['within_days' => $valuationThresholdDays]) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">Valuation CSV</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Fiscal Year</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Assessed</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reconciliationRows as $assessment)
                        @php
                            $paid = (float) ($assessment->payments_sum_amount ?? 0);
                            $outstanding = max((float) $assessment->tax_amount - $paid, 0);
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-gray-900">{{ $assessment->property?->title }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $assessment->owner?->display_name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $assessment->fiscal_year }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @class([
                                        'bg-gray-100 text-gray-800' => $assessment->status === 'draft',
                                        'bg-yellow-100 text-yellow-800' => $assessment->status === 'issued',
                                        'bg-red-100 text-red-800' => $assessment->status === 'overdue',
                                        'bg-green-100 text-green-800' => $assessment->status === 'paid',
                                    ])
                                >{{ ucfirst($assessment->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-900">BDT {{ number_format($assessment->tax_amount, 2) }}</td>
                            <td class="px-4 py-3 text-gray-600">BDT {{ number_format($paid, 2) }}</td>
                            <td class="px-4 py-3 font-semibold {{ $outstanding > 0 ? 'text-red-600' : 'text-green-600' }}">
                                BDT {{ number_format($outstanding, 2) }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ optional($assessment->due_date)->format('M d, Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">No assessments match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $reconciliationRows->links() }}
        </div>
    </div>
</div>
@endsection
