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

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    @if($property->owner_id === auth()->id())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('citizen.properties.request.update', $property) }}" class="bg-blue-600 text-white px-4 py-3 rounded text-center">Request Update</a>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Pending Rental Requests</h3>
                <span class="text-sm text-gray-500">{{ $pendingRentalRequests->count() }} waiting</span>
            </div>
            @if($pendingRentalRequests->isEmpty())
                <p class="text-sm text-gray-500 mt-4">No rental requests for this property.</p>
            @else
                <div class="mt-4 space-y-4">
                    @foreach($pendingRentalRequests as $rentalRequest)
                        <div class="border border-gray-200 rounded-lg p-4 flex flex-col gap-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $rentalRequest->user->display_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $rentalRequest->user->email }}</p>
                                    <p class="text-xs text-gray-500">Requested {{ $rentalRequest->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $rentalRequest->ready_for_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $rentalRequest->ready_for_admin ? 'Forwarded to admin' : 'Awaiting owner review' }}
                                </span>
                            </div>
                            @if($rentalRequest->message)
                                <div class="text-sm text-gray-700 bg-gray-50 rounded p-3">{{ $rentalRequest->message }}</div>
                            @endif
                            <dl class="grid grid-cols-2 gap-3 text-xs text-gray-600">
                                <div>
                                    <dt class="uppercase tracking-wide">Tenant preferred start</dt>
                                    <dd class="font-semibold text-gray-900">{{ optional($rentalRequest->tenant_start_date)->format('M d, Y') ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="uppercase tracking-wide">Tenant preferred end</dt>
                                    <dd class="font-semibold text-gray-900">{{ optional($rentalRequest->tenant_end_date)->format('M d, Y') ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="uppercase tracking-wide">Proposed rent</dt>
                                    <dd class="font-semibold text-gray-900">{{ $rentalRequest->tenant_monthly_rent ? 'BDT ' . number_format($rentalRequest->tenant_monthly_rent, 2) : '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="uppercase tracking-wide">Security deposit</dt>
                                    <dd class="font-semibold text-gray-900">{{ $rentalRequest->tenant_security_deposit ? 'BDT ' . number_format($rentalRequest->tenant_security_deposit, 2) : '—' }}</dd>
                                </div>
                            </dl>

                            @if(!$rentalRequest->ready_for_admin)
                                <form method="POST" action="{{ route('citizen.rental-requests.owner-confirm', $rentalRequest) }}" class="space-y-3 mt-3">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-xs font-semibold text-gray-600">Start date</label>
                                            <input type="date" name="owner_start_date" value="{{ old('owner_start_date', optional($rentalRequest->tenant_start_date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold text-gray-600">End date</label>
                                            <input type="date" name="owner_end_date" value="{{ old('owner_end_date', optional($rentalRequest->tenant_end_date)->format('Y-m-d')) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold text-gray-600">Monthly rent (BDT)</label>
                                            <input type="number" step="0.01" name="owner_monthly_rent" value="{{ old('owner_monthly_rent', $rentalRequest->tenant_monthly_rent) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm" required>
                                        </div>
                                        <div>
                                            <label class="text-xs font-semibold text-gray-600">Security deposit (BDT)</label>
                                            <input type="number" step="0.01" name="owner_security_deposit" value="{{ old('owner_security_deposit', $rentalRequest->tenant_security_deposit) }}" class="mt-1 w-full border rounded px-3 py-2 text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600">Owner note to admin (optional)</label>
                                        <textarea name="owner_notes" rows="2" class="mt-1 w-full border rounded px-3 py-2 text-sm" placeholder="Add extra context or requirements">{{ old('owner_notes') }}</textarea>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <a href="mailto:{{ $rentalRequest->user->email }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded border border-gray-300 text-gray-700">Contact Tenant</a>
                                        <button class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded">Forward to Admin</button>
                                    </div>
                                </form>
                            @else
                                <div class="flex justify-between items-center text-xs text-gray-600">
                                    <div>
                                        <p>Forwarded on {{ $rentalRequest->owner_confirmed_at?->format('M d, Y h:i A') }}</p>
                                        @if($rentalRequest->owner_notes)
                                            <p class="text-gray-500">Owner note: {{ $rentalRequest->owner_notes }}</p>
                                        @endif
                                    </div>
                                    <a href="mailto:{{ $rentalRequest->user->email }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded border border-gray-300 text-gray-700">Contact Tenant</a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="mt-6">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Submit Rental Request</h3>

                @if($activeAgreement)
                    <div class="bg-blue-50 border border-blue-200 text-blue-900 rounded p-4 text-sm">
                        This property already has an active rent agreement through {{ optional($activeAgreement->end_date)->format('M d, Y') ?? 'the current term' }}.
                        Rental requests are disabled until that agreement ends.
                    </div>
                @else
                    <form method="POST" action="{{ route('citizen.properties.rental-request', $property) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Desired start date</label>
                                <input type="date" name="tenant_start_date" value="{{ old('tenant_start_date') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Desired end date</label>
                                <input type="date" name="tenant_end_date" value="{{ old('tenant_end_date') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Monthly rent (set by owner)</label>
                                @if($property->rent_price)
                                    <div class="mt-1 w-full border rounded px-3 py-2 bg-gray-50 text-gray-800">BDT {{ number_format($property->rent_price, 2) }}</div>
                                    <input type="hidden" name="tenant_monthly_rent" value="{{ $property->rent_price }}">
                                    <p class="text-xs text-gray-500 mt-1">This amount is provided by the property owner.</p>
                                @else
                                    <input type="number" step="0.01" name="tenant_monthly_rent" value="{{ old('tenant_monthly_rent') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                                    <p class="text-xs text-yellow-600 mt-1">Owner has not set a rent yet. Please propose a reasonable amount.</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Security deposit (optional)</label>
                                <input type="number" step="0.01" name="tenant_security_deposit" value="{{ old('tenant_security_deposit') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Message to owner (optional)</label>
                            <textarea name="message" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('message') }}</textarea>
                        </div>
                        <button class="w-full bg-green-600 text-white py-2 rounded font-semibold">Send Rental Request</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
