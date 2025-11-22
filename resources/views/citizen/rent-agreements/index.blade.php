@extends('layouts.citizen')

@section('title', 'Rent Agreements')
@section('page-title', 'My Rent Agreements')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Agreement #</th>
                    <th class="px-4 py-3 text-left">Property</th>
                    <th class="px-4 py-3 text-left">Role</th>
                    <th class="px-4 py-3 text-left">Start</th>
                    <th class="px-4 py-3 text-left">End</th>
                    <th class="px-4 py-3 text-left">Rent</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($agreements as $agreement)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $agreement->agreement_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $agreement->property?->title }}</td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $agreement->tenant_id === auth()->id() ? 'Tenant' : 'Landlord' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $agreement->start_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $agreement->end_date->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-900">BDT {{ number_format($agreement->monthly_rent, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('citizen.rent-agreements.show', $agreement) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No rent agreements found.</td>
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
