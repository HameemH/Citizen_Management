@extends('layouts.citizen')

@section('title', 'Properties')
@section('page-title', 'My Properties')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">My Registered Properties</h2>
                <p class="text-sm text-gray-500">Track approved properties, pending rental requests, and request data updates.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('citizen.properties.request.add') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md shadow hover:bg-green-700">Request New Property</a>
            </div>
        </div>

        <div class="p-6">
            @if($ownedProperties->isEmpty())
                <div class="text-center text-gray-500">
                    <p>You have not registered any properties yet.</p>
                    <a href="{{ route('citizen.properties.request.add') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-green-500 text-green-700 rounded-md">Submit a property for review</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($ownedProperties as $property)
                        <div class="border border-gray-100 rounded-lg p-5 space-y-3 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-500">{{ ucfirst($property->type) }}</p>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $property->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $property->address_line }}, {{ $property->city }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    @if($property->active_rental_count)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Currently rented</span>
                                    @endif
                                    @if($property->pending_rental_requests_count)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $property->pending_rental_requests_count }} rental request(s)</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-sm text-gray-600">{{ Str::limit($property->description, 140) }}</p>

                            <dl class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-gray-500">Size</dt>
                                    <dd class="font-semibold">{{ number_format($property->area_sqft ?? 0) }} sqft</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-gray-500">Rent status</dt>
                                    <dd class="font-semibold">
                                        @if($property->active_rental_count)
                                            Currently rented
                                        @elseif($property->is_available_for_rent)
                                            Available for rent
                                        @else
                                            Not listed for rent
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-gray-500">Assessed value</dt>
                                    <dd class="font-semibold">৳ {{ number_format($property->assessed_value ?? 0, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs uppercase tracking-wide text-gray-500">Last valuation</dt>
                                    <dd class="font-semibold">{{ optional($property->last_valuation_at)->format('M d, Y') ?? 'Pending' }}</dd>
                                </div>
                            </dl>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <a href="{{ route('citizen.properties.show', $property) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md">View Details</a>
                                <a href="{{ route('citizen.properties.request.update', $property) }}" class="inline-flex items-center px-4 py-2 border border-green-600 text-green-700 text-sm font-semibold rounded-md">Request Update</a>
                                <a href="{{ route('citizen.properties.request.transfer', $property) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-md">Transfer Ownership</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-900">My Property Requests</h4>
                <p class="text-sm text-gray-500">Track add, update, and transfer requests with their latest status.</p>
            </div>
            <a href="{{ route('citizen.properties.request.add') }}" class="text-sm text-green-700 font-semibold">+ Submit another request</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Property</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Submitted</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($userRequests as $request)
                        <tr>
                            <td class="px-4 py-3 font-semibold capitalize">{{ $request->type }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $request->property?->title ?? data_get($request->payload, 'title', 'New property submission') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold @class([
                                    'bg-yellow-100 text-yellow-800' => $request->status === 'pending',
                                    'bg-green-100 text-green-800' => $request->status === 'approved',
                                    'bg-red-100 text-red-800' => $request->status === 'rejected',
                                ])">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">No property requests submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-900">Explore Community Properties</h4>
                <p class="text-sm text-gray-500">Browse other properties available within the municipality.</p>
            </div>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3 w-full md:w-auto">
                <input type="text" name="q" value="{{ request('q') }}" class="rounded-md border-gray-300" placeholder="Title or city">
                <label class="inline-flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="rent_only" value="1" class="rounded" {{ request('rent_only') ? 'checked' : '' }}>
                    <span class="ml-2">Rent availability</span>
                </label>
                <button type="submit" class="px-4 py-2 border rounded text-gray-700">Filter</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($properties as $property)
                <div class="border border-gray-100 rounded-lg p-5 space-y-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500">{{ ucfirst($property->type) }}</p>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $property->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $property->address_line }}, {{ $property->city }}</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-400">Owner: {{ optional($property->owner)->display_name ?? 'Municipality' }}</span>
                    </div>
                    <p class="text-sm text-gray-600">{{ Str::limit($property->description, 120) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>{{ $property->is_available_for_rent ? 'Accepting rental inquiries' : 'Not for rent' }}</span>
                        <a href="{{ route('citizen.properties.show', $property) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded border border-green-600 text-green-700">View details</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No properties found.</p>
            @endforelse
        </div>

        <div>
            {{ $properties->links() }}
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h4 class="text-lg font-semibold text-gray-900">My Rental Activity</h4>
                <p class="text-sm text-gray-500">See where you currently rent and track pending municipal approvals.</p>
            </div>
        </div>

        @if($activeRentals->isEmpty() && $userRentalRequests->isEmpty())
            <p class="text-sm text-gray-500">You haven’t submitted any rental requests yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h5 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Active Rentals</h5>
                    @forelse($activeRentals as $agreement)
                        <div class="border border-gray-100 rounded-lg p-4 space-y-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-900">{{ $agreement->property->title ?? 'Property removed' }}</p>
                                <span class="text-xs text-gray-500">Landlord: {{ optional($agreement->property?->owner)->display_name ?? 'Municipality' }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $agreement->property->address_line ?? 'Address unavailable' }}, {{ $agreement->property->city ?? '' }}</p>
                            <p class="text-sm text-gray-700">BDT {{ number_format($agreement->monthly_rent, 2) }} / month</p>
                            <p class="text-xs text-gray-500">{{ $agreement->start_date?->format('M d, Y') }} - {{ $agreement->end_date?->format('M d, Y') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No active rental agreements.</p>
                    @endforelse
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Submitted Rental Requests</h5>
                    <div class="border border-gray-100 rounded-lg overflow-hidden">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-4 py-2 text-left">Property</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Submitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($userRentalRequests as $request)
                                    <tr>
                                        <td class="px-4 py-2">
                                            <p class="font-semibold text-gray-900">{{ $request->property->title ?? 'Property removed' }}</p>
                                            <p class="text-xs text-gray-500">Owner: {{ optional($request->property?->owner)->display_name ?? 'Municipality' }}</p>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold @class([
                                                'bg-yellow-100 text-yellow-800' => $request->status === 'pending',
                                                'bg-green-100 text-green-800' => $request->status === 'approved',
                                                'bg-red-100 text-red-800' => $request->status === 'rejected',
                                            ])">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-sm text-gray-500 text-center">No rental requests submitted.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
