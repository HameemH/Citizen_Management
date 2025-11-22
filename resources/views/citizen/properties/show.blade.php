@extends('layouts.citizen')

@section('title', $property->title)
@section('page-title', 'Property Details')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $property->title }}</h2>
                <p class="text-gray-600">{{ $property->address_line }}, {{ $property->city }}, {{ $property->state }}</p>
                <p class="mt-2 text-sm text-gray-500">Type: {{ ucfirst($property->type) }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Owner</p>
                <p class="font-semibold">{{ optional($property->owner)->display_name ?? 'Unassigned' }}</p>
            </div>
        </div>

        <div class="mt-4 border-t pt-4">
            <p class="text-gray-700">{{ $property->description ?? 'No description provided.' }}</p>
        </div>

        <div class="mt-4 flex gap-4 text-sm text-gray-600">
            <span>Area: {{ $property->area_sqft ?? 'N/A' }} sqft</span>
            <span>Status: {{ $property->is_active ? 'Active' : 'Inactive' }}</span>
            <span>Rent: {{ $property->is_available_for_rent ? 'Available' : 'Not available' }}</span>
        </div>

        <div class="mt-6 border-t pt-4">
            <p class="text-sm font-semibold text-gray-700 mb-2">Valuation Snapshot</p>

            @if($canViewValuation)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-gray-500">Assessed Value</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->assessed_value ? 'BDT ' . number_format($property->assessed_value, 2) : 'Not set' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-gray-500">Land Use</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->land_use ?? 'Not set' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded p-3">
                        <p class="text-gray-500">Last Valuation</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $property->last_valuation_at ? $property->last_valuation_at->format('M d, Y') : 'Not recorded' }}</p>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 rounded p-4 text-sm">
                    Valuation data is restricted to the property owner. Request the owner to share details if you need verification.
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @if($property->owner_id === auth()->id())
            <a href="{{ route('citizen.properties.request.update', $property) }}" class="bg-blue-600 text-white px-4 py-3 rounded text-center">Request Update</a>
            <a href="{{ route('citizen.properties.request.transfer', $property) }}" class="bg-yellow-500 text-white px-4 py-3 rounded text-center">Request Transfer</a>
        @endif
        <form method="POST" action="{{ route('citizen.properties.rental-request', $property) }}" class="md:col-span-{{ $property->owner_id === auth()->id() ? '1' : '3' }}">
            @csrf
            <div class="bg-white shadow rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700">Rental message (optional)</label>
                <textarea name="message" rows="2" class="mt-1 w-full border rounded"></textarea>
                <button class="mt-3 w-full bg-green-600 text-white py-2 rounded">Send Rental Request</button>
            </div>
        </form>
    </div>
</div>
@endsection
