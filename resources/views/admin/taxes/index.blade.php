@extends('layouts.admin')

@section('title', 'Tax Assessments')
@section('page-title', 'Tax Assessments')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Assessment Overview</h2>
            <p class="text-sm text-gray-600">Monitor issued, overdue, and paid property tax assessments.</p>
        </div>
        <a href="{{ route('admin.taxes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded shadow">Generate Assessment</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Issued</p>
            <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['issued']) }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Overdue</p>
            <p class="text-2xl font-semibold text-red-600">{{ number_format($stats['overdue']) }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Outstanding (BDT)</p>
            <p class="text-2xl font-semibold text-yellow-600">{{ number_format($stats['outstanding_total'], 2) }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">Paid</p>
            <p class="text-2xl font-semibold text-green-600">{{ number_format($stats['paid']) }}</p>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search property or owner" class="border rounded p-2">
            <input type="text" name="fiscal_year" value="{{ $filters['fiscal_year'] ?? '' }}" placeholder="Fiscal Year" class="border rounded p-2">
            <select name="status" class="border rounded p-2">
                <option value="">All Statuses</option>
                @foreach(['draft','issued','overdue','paid','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="bg-gray-900 text-white rounded p-2">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiscal Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assessments as $assessment)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $assessment->property->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assessment->owner->display_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assessment->fiscal_year }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">BDT {{ number_format($assessment->tax_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @class([
                                        'bg-yellow-100 text-yellow-800' => $assessment->status === 'issued',
                                        'bg-green-100 text-green-800' => $assessment->status === 'paid',
                                        'bg-red-100 text-red-800' => $assessment->status === 'overdue',
                                        'bg-gray-100 text-gray-800' => in_array($assessment->status, ['draft','cancelled']),
                                    ])
                                >
                                    {{ ucfirst($assessment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $assessment->due_date ? $assessment->due_date->format('M d, Y') : '—' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.taxes.show', $assessment) }}" class="text-indigo-600 text-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No assessments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $assessments->links() }}
        </div>
    </div>
</div>
@endsection
