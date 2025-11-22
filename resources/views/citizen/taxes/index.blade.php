@extends('layouts.citizen')

@section('title', 'My Taxes')
@section('page-title', 'My Tax Assessments')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Outstanding Overview</h2>
                <p class="text-sm text-gray-600">Track property tax dues and payments.</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-red-600">BDT {{ number_format($outstandingTotal, 2) }}</p>
                <p class="text-xs text-gray-500">Total outstanding</p>
                @if($nextDueDate)
                    <p class="text-xs text-gray-500 mt-1">Next due: {{ $nextDueDate->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4 space-y-4">
        @if(session('status'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @unless($stripeEnabled)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-3 text-sm">
                Online card payments are temporarily unavailable. Please contact support or visit the ward office to pay.
            </div>
        @endunless

        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <select name="status" class="border rounded p-2 md:w-48">
                <option value="">All statuses</option>
                @foreach(['issued' => 'Issued', 'overdue' => 'Overdue', 'paid' => 'Paid', 'draft' => 'Draft'] as $value => $label)
                    <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="bg-green-600 text-white rounded px-4 py-2 md:w-auto">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiscal Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assessments as $assessment)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $assessment->property->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assessment->fiscal_year }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">BDT {{ number_format($assessment->tax_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $assessment->status === 'issued',
                                        'bg-red-100 text-red-800' => $assessment->status === 'overdue',
                                        'bg-green-100 text-green-800' => $assessment->status === 'paid',
                                        'bg-gray-100 text-gray-800' => $assessment->status === 'draft',
                                    ])
                                >
                                    {{ ucfirst($assessment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assessment->due_date ? $assessment->due_date->format('M d, Y') : '—' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if(in_array($assessment->status, ['issued', 'overdue']) && $stripeEnabled)
                                    <form method="POST" action="{{ route('citizen.taxes.pay', $assessment) }}">
                                        @csrf
                                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold">Pay with Stripe</button>
                                    </form>
                                @elseif($assessment->status === 'paid')
                                    <span class="text-green-700 font-semibold text-xs">Paid</span>
                                @else
                                    <span class="text-gray-500 text-xs">No action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No assessments available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $assessments->links() }}
        </div>
    </div>

    <div class="bg-white rounded shadow">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                <p class="text-xs text-gray-500">Last 10 payments across your properties.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receipt</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td class="px-6 py-4 text-gray-900">{{ optional($payment->paid_at)->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $payment->assessment?->property?->title ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $payment->assessment?->fiscal_year }}</td>
                            <td class="px-6 py-4 font-semibold text-green-700">BDT {{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ ucfirst($payment->method ?? 'manual') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('citizen.taxes.payments.receipt', $payment) }}" class="text-sm text-indigo-600 hover:underline">Download</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No payments recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
