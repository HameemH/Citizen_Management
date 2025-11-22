@extends('layouts.admin')

@section('title', 'Rental Requests')
@section('page-title', 'Rental Requests')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Incoming Rental Requests</h2>
            <a href="{{ route('admin.properties.index') }}" class="text-sm text-gray-500">Back</a>
        </div>
        <div class="divide-y">
            @forelse($requests as $request)
                <div class="px-6 py-5 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $request->property->title }}</p>
                            <p class="text-sm text-gray-500">Requester: {{ $request->user->display_name }} · {{ $request->created_at->diffForHumans() }}</p>
                            <p class="text-sm text-gray-500">Owner: {{ $request->property->owner->display_name }} ({{ $request->property->owner->email }})</p>
                            <p class="text-sm text-gray-600">{{ $request->message ?? 'No tenant message provided.' }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $request->ready_for_admin ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $request->ready_for_admin ? 'Ready for approval' : 'Waiting for owner confirmation' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Tenant Proposal</h3>
                            <dl class="mt-2 space-y-1 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <dt>Start date</dt>
                                    <dd>{{ optional($request->tenant_start_date)->format('M d, Y') ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>End date</dt>
                                    <dd>{{ optional($request->tenant_end_date)->format('M d, Y') ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Monthly rent</dt>
                                    <dd>{{ $request->tenant_monthly_rent ? 'BDT ' . number_format($request->tenant_monthly_rent, 2) : '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Security deposit</dt>
                                    <dd>{{ $request->tenant_security_deposit ? 'BDT ' . number_format($request->tenant_security_deposit, 2) : '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Owner Confirmation</h3>
                            @if($request->ready_for_admin)
                                <dl class="mt-2 space-y-1 text-sm text-gray-700">
                                    <div class="flex justify-between">
                                        <dt>Start date</dt>
                                        <dd>{{ optional($request->owner_start_date)->format('M d, Y') ?? '—' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt>End date</dt>
                                        <dd>{{ optional($request->owner_end_date)->format('M d, Y') ?? '—' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt>Monthly rent</dt>
                                        <dd>{{ $request->owner_monthly_rent ? 'BDT ' . number_format($request->owner_monthly_rent, 2) : '—' }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt>Security deposit</dt>
                                        <dd>{{ $request->owner_security_deposit ? 'BDT ' . number_format($request->owner_security_deposit, 2) : '—' }}</dd>
                                    </div>
                                    @if($request->owner_notes)
                                        <p class="text-xs text-gray-500 mt-2">Owner note: {{ $request->owner_notes }}</p>
                                    @endif
                                </dl>
                            @else
                                <p class="text-sm text-gray-600 mt-2">Owner has not confirmed terms yet.</p>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.rental-requests.handle', $request) }}" class="grid gap-3 md:grid-cols-6">
                        @csrf
                        <input type="date" name="start_date" class="border rounded p-2" value="{{ optional($request->owner_start_date ?? $request->tenant_start_date)->format('Y-m-d') }}" placeholder="Start date">
                        <input type="date" name="end_date" class="border rounded p-2" value="{{ optional($request->owner_end_date ?? $request->tenant_end_date)->format('Y-m-d') }}" placeholder="End date">
                        <input type="number" name="monthly_rent" step="0.01" class="border rounded p-2" value="{{ $request->owner_monthly_rent ?? $request->tenant_monthly_rent }}" placeholder="Monthly rent">
                        <input type="number" name="security_deposit" step="0.01" class="border rounded p-2" value="{{ $request->owner_security_deposit ?? $request->tenant_security_deposit }}" placeholder="Security deposit">
                        <input type="text" name="decision_note" placeholder="Decision note" class="border rounded p-2 md:col-span-2">
                        <textarea name="terms_text" rows="2" class="border rounded p-2 md:col-span-4" placeholder="Custom terms (optional)"></textarea>
                        <div class="flex gap-2 md:col-span-2">
                            <button name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded {{ $request->ready_for_admin ? '' : 'opacity-50 cursor-not-allowed' }}" {{ $request->ready_for_admin ? '' : 'disabled' }}>Approve</button>
                            <button name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
                        </div>
                    </form>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-gray-500">No rental requests.</p>
            @endforelse
        </div>
        <div class="px-6 py-4">{{ $requests->links() }}</div>
    </div>
</div>
@endsection
