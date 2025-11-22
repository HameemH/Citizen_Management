@extends('layouts.admin')

@section('title', 'Rent Agreements')
@section('page-title', 'Rent Agreements')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" class="flex flex-wrap gap-2 text-sm">
            <select name="status" class="border rounded px-2 py-1">
                <option value="">All statuses</option>
                @foreach(['active' => 'Active', 'ended' => 'Ended'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search property or tenant" class="border rounded px-2 py-1">
            <button class="px-3 py-1 bg-indigo-600 text-white rounded">Filter</button>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Agreement #</th>
                    <th class="px-4 py-3 text-left">Property</th>
                    <th class="px-4 py-3 text-left">Tenant</th>
                    <th class="px-4 py-3 text-left">Start</th>
                    <th class="px-4 py-3 text-left">End</th>
                    <th class="px-4 py-3 text-left">Rent</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($agreements as $agreement)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $agreement->agreement_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $agreement->property?->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $agreement->tenant?->display_name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $agreement->start_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $agreement->end_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-900">BDT {{ number_format($agreement->monthly_rent, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $agreement->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($agreement->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.rent-agreements.show', $agreement) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">No rent agreements yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $agreements->links() }}
        </div>
    </div>
</div>
@endsection
