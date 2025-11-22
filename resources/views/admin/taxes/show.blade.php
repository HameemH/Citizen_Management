@extends('layouts.admin')

@section('title', 'Assessment Details')
@section('page-title', 'Assessment Details')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <p class="text-sm text-gray-500">Property</p>
                <h2 class="text-2xl font-semibold text-gray-900">{{ $assessment->property->title }}</h2>
                <p class="text-sm text-gray-600">Owner: {{ optional($assessment->owner)->display_name }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold @class([
                    'bg-yellow-100 text-yellow-800' => $assessment->status === 'issued',
                    'bg-green-100 text-green-800' => $assessment->status === 'paid',
                    'bg-red-100 text-red-800' => $assessment->status === 'overdue',
                    'bg-gray-100 text-gray-800' => in_array($assessment->status, ['draft','cancelled']),
                ])">
                    {{ ucfirst($assessment->status) }}
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
            <div class="bg-gray-50 rounded p-3">
                <p class="text-gray-500">Fiscal Year</p>
                <p class="text-lg font-semibold text-gray-900">{{ $assessment->fiscal_year }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-gray-500">Assessed Value</p>
                <p class="text-lg font-semibold text-gray-900">BDT {{ number_format($assessment->assessed_value_snapshot, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-gray-500">Tax Amount</p>
                <p class="text-lg font-semibold text-gray-900">BDT {{ number_format($assessment->tax_amount, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded p-3">
                <p class="text-gray-500">Due Date</p>
                <p class="text-lg font-semibold text-gray-900">{{ $assessment->due_date ? $assessment->due_date->format('M d, Y') : 'Not set' }}</p>
            </div>
        </div>
        @if($assessment->notes)
            <div class="bg-gray-50 rounded p-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Notes</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $assessment->notes }}</p>
            </div>
        @endif
        @if($assessment->status === 'draft')
            <div class="border rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Issue Assessment</p>
                <form method="POST" action="{{ route('admin.taxes.issue', $assessment) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-700">Due Date</label>
                        <input type="date" name="due_date" value="{{ optional($assessment->due_date)->format('Y-m-d') }}" class="mt-1 w-full border rounded">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-700">Notes</label>
                        <input type="text" name="notes" class="mt-1 w-full border rounded" placeholder="Optional message to citizen">
                    </div>
                    <div class="md:col-span-3 flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Issue Assessment</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                <p class="text-sm text-gray-600">Collected {{ number_format($paymentTotal, 2) }} of {{ number_format($assessment->tax_amount, 2) }} BDT.</p>
            </div>
        </div>
        <div class="divide-y">
            @forelse($assessment->payments as $payment)
                <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">BDT {{ number_format($payment->amount, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->method ?? 'manual' }} • {{ optional($payment->paid_at)->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-400">Reference: {{ $payment->reference ?? '—' }}</p>
                    </div>
                    <div class="text-xs text-gray-500 mt-2 md:mt-0">
                        Recorded by {{ optional($payment->recorder)->display_name ?? 'System' }}
                    </div>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-gray-500">No payments recorded yet.</p>
            @endforelse
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            <form method="POST" action="{{ route('admin.taxes.payments.store', $assessment) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700">Amount (BDT)</label>
                    <input type="number" name="amount" step="0.01" class="mt-1 w-full border rounded" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Method</label>
                    <input type="text" name="method" class="mt-1 w-full border rounded" placeholder="cash, bank, etc.">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Reference</label>
                    <input type="text" name="reference" class="mt-1 w-full border rounded" placeholder="Receipt no.">
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Paid At</label>
                    <input type="date" name="paid_at" class="mt-1 w-full border rounded" value="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-sm text-gray-700">Notes</label>
                    <textarea name="notes" rows="2" class="mt-1 w-full border rounded"></textarea>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button class="px-4 py-2 bg-green-600 text-white rounded">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
