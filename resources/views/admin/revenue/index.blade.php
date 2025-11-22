@extends('layouts.admin')

@section('title', 'Revenue Dashboard')
@section('page-title', 'Revenue & Compliance')

@section('content')
<div class="admin-revenue-shell space-y-8 text-[#1f2340]">
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="admin-revenue-card">
            <p class="text-xs uppercase tracking-[0.25em] text-[#6b7097]">Issued YTD</p>
            <p class="mt-3 text-2xl font-semibold">BDT {{ number_format($kpis['issued_total'], 2) }}</p>
        </div>
        <div class="admin-revenue-card">
            <p class="text-xs uppercase tracking-[0.25em] text-[#6b7097]">Collected YTD</p>
            <p class="mt-3 text-2xl font-semibold">BDT {{ number_format($kpis['collected_total'], 2) }}</p>
        </div>
        <div class="admin-revenue-card">
            <p class="text-xs uppercase tracking-[0.25em] text-[#6b7097]">Outstanding</p>
            <p class="mt-3 text-2xl font-semibold">BDT {{ number_format($kpis['outstanding_total'], 2) }}</p>
        </div>
        <div class="admin-revenue-card">
            <p class="text-xs uppercase tracking-[0.25em] text-[#6b7097]">Collection Rate</p>
            <p class="mt-3 text-2xl font-semibold">{{ number_format($kpis['collection_rate'], 1) }}%</p>
            <p class="text-xs text-[#6b7097]">Avg days to pay: {{ $kpis['avg_days_to_pay'] }} days</p>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="admin-revenue-card admin-revenue-card--flat">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Issued vs Collected (last 6 months)</h3>
            </div>
            <div class="space-y-4">
                @foreach($trend as $point)
                    @php
                        $collectionRatio = $point['issued'] > 0
                            ? min(100, ($point['collected'] / max($point['issued'], 1)) * 100)
                            : ($point['collected'] > 0 ? 100 : 0);
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm text-[#4b516c]">
                            <span>{{ $point['label'] }}</span>
                            <span>Issued: BDT {{ number_format($point['issued'], 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-[#6b7097]">
                            <span>Collected: BDT {{ number_format($point['collected'], 0) }}</span>
                            <span class="font-semibold">{{ number_format($collectionRatio, 0) }}%</span>
                        </div>
                        <div class="mt-1 h-2 rounded-full bg-[#ccd0f3]">
                            <div class="h-2 rounded-full bg-[#4f5589]" style="width: {{ $collectionRatio }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="admin-revenue-card admin-revenue-card--flat">
            <h3 class="text-lg font-semibold mb-4">Aging Buckets</h3>
            <div class="space-y-4">
                @foreach($agingBuckets as $bucket)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium">{{ $bucket['label'] }}</p>
                            <p class="text-xs text-[#6b7097]">{{ $bucket['count'] }} accounts</p>
                        </div>
                        <p class="text-sm font-semibold">BDT {{ number_format($bucket['amount'], 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="admin-revenue-card admin-revenue-card--flat">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Top Delinquent Owners</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-[#6b7097] uppercase text-xs tracking-[0.2em]">
                            <th class="py-2">Owner</th>
                            <th class="py-2">Properties</th>
                            <th class="py-2">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#dfe2f8]">
                        @forelse($delinquents as $row)
                            <tr>
                                <td class="py-2">{{ $row['owner']?->display_name ?? 'N/A' }}</td>
                                <td class="py-2 text-[#4b516c]">{{ $row['property_count'] }}</td>
                                <td class="py-2 font-semibold">BDT {{ number_format($row['outstanding'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-[#6b7097]">No delinquent records at the moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="admin-revenue-card admin-revenue-card--flat">
            <h3 class="text-lg font-semibold mb-2">Alerts</h3>
            <p class="text-sm text-[#4b516c]">{{ $overdueCount }} assessments are currently overdue. Consider proactive outreach or reminders.</p>
            <a href="{{ route('admin.taxes.index', ['status' => 'overdue']) }}" class="admin-revenue-cta mt-6">Review overdue list</a>
        </div>
    </div>

    <div class="admin-revenue-card admin-revenue-card--flat">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold">Upcoming Valuations (next {{ $valuationThresholdDays }} days)</h3>
                <p class="text-sm text-[#6b7097]">Monitor properties whose valuation cycle is about to lapse.</p>
            </div>
            <a href="{{ route('admin.revenue.export-valuations', ['within_days' => $valuationThresholdDays]) }}" class="admin-revenue-link inline-flex items-center gap-2">Export list</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-[#6b7097] uppercase text-xs tracking-[0.2em]">
                        <th class="py-2">Property</th>
                        <th class="py-2">Owner</th>
                        <th class="py-2">City</th>
                        <th class="py-2">Last Valuation</th>
                        <th class="py-2">Next Due</th>
                        <th class="py-2 text-right">Days Remaining</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dfe2f8]">
                    @forelse($upcomingValuations as $row)
                        <tr>
                            <td class="py-2 font-semibold">{{ $row['property']->title }}</td>
                            <td class="py-2 text-[#4b516c]">{{ $row['owner']?->display_name ?? 'N/A' }}</td>
                            <td class="py-2 text-[#4b516c]">{{ $row['property']->city }}</td>
                            <td class="py-2 text-[#6b7097]">{{ optional($row['last_valuation_at'])->format('M d, Y') ?? '—' }}</td>
                            <td class="py-2 text-[#6b7097]">{{ optional($row['next_due'])->format('M d, Y') ?? '—' }}</td>
                            <td class="py-2 text-right font-semibold {{ $row['is_overdue'] ? 'text-[#752534]' : '' }}">
                                {{ $row['days_until'] < 0 ? ($row['days_until'] * -1) . ' days overdue' : $row['days_until'] . ' days' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-[#6b7097]">All valuations are up to date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-revenue-card admin-revenue-card--flat admin-revenue-card--table">
        <div class="p-6 border-b border-[#d7daf3] flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex flex-wrap items-center gap-2 text-sm">
                <select name="status" class="rounded-full border border-[#cad0f4] bg-white/70 px-3 py-2 focus:border-[#7b81c8] focus:ring-0">
                    <option value="">All statuses</option>
                    @foreach(['draft' => 'Draft', 'issued' => 'Issued', 'overdue' => 'Overdue', 'paid' => 'Paid'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)> {{ $label }} </option>
                    @endforeach
                </select>
                <select name="fiscal_year" class="rounded-full border border-[#cad0f4] bg-white/70 px-3 py-2 focus:border-[#7b81c8] focus:ring-0">
                    <option value="">All years</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" @selected(($filters['fiscal_year'] ?? '') === $year)>{{ $year }}</option>
                    @endforeach
                </select>
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="City" class="rounded-full border border-[#cad0f4] bg-white/70 px-3 py-2 focus:border-[#7b81c8] focus:ring-0" />
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search property/owner" class="rounded-full border border-[#cad0f4] bg-white/70 px-3 py-2 focus:border-[#7b81c8] focus:ring-0" />
                <button class="rounded-full border border-[#2a3063] px-4 py-2 text-sm font-semibold text-[#2a3063] hover:bg-[#2a3063] hover:text-white transition">Filter</button>
            </form>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('admin.revenue.export', request()->query()) }}" class="admin-revenue-link">Download CSV</a>
                <a href="{{ route('admin.revenue.export-valuations', ['within_days' => $valuationThresholdDays]) }}" class="admin-revenue-link">Valuation CSV</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="admin-revenue-table min-w-full divide-y divide-[#dfe2f8] text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Property</th>
                        <th class="px-4 py-3 text-left font-medium">Owner</th>
                        <th class="px-4 py-3 text-left font-medium">Fiscal Year</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Assessed</th>
                        <th class="px-4 py-3 text-left font-medium">Paid</th>
                        <th class="px-4 py-3 text-left font-medium">Outstanding</th>
                        <th class="px-4 py-3 text-left font-medium">Due Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#dfe2f8]">
                    @forelse($reconciliationRows as $assessment)
                        @php
                            $paid = (float) ($assessment->payments_sum_amount ?? 0);
                            $outstanding = max((float) $assessment->tax_amount - $paid, 0);
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $assessment->property?->title }}</td>
                            <td class="px-4 py-3 text-[#4b516c]">{{ $assessment->owner?->display_name }}</td>
                            <td class="px-4 py-3 text-[#4b516c]">{{ $assessment->fiscal_year }}</td>
                            <td class="px-4 py-3">
                                <span class="admin-revenue-chip" data-variant="{{ $assessment->status }}">{{ ucfirst($assessment->status) }}</span>
                            </td>
                            <td class="px-4 py-3">BDT {{ number_format($assessment->tax_amount, 2) }}</td>
                            <td class="px-4 py-3 text-[#4b516c]">BDT {{ number_format($paid, 2) }}</td>
                            <td class="px-4 py-3 font-semibold @class(['text-[#752534]' => $outstanding > 0, 'text-[#1f4d57]' => $outstanding <= 0])">
                                BDT {{ number_format($outstanding, 2) }}
                            </td>
                            <td class="px-4 py-3 text-[#4b516c]">{{ optional($assessment->due_date)->format('M d, Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-[#6b7097]">No assessments match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#d7daf3]">
            {{ $reconciliationRows->links() }}
        </div>
    </div>
</div>
@endsection
