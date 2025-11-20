@extends('layouts.admin')

@section('title', 'Property Requests')
@section('page-title', 'Property Requests')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Pending Requests</h2>
            <a href="{{ route('admin.properties.index') }}" class="text-sm text-gray-500">Back to properties</a>
        </div>
        <div class="divide-y">
            @forelse($requests as $request)
                <div class="px-6 py-4 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex-1 space-y-3">
                        <div>
                            <p class="font-semibold capitalize">{{ $request->type }} request</p>
                            <p class="text-sm text-gray-500">
                                Submitted by {{ $request->user->display_name }}
                                @if($request->property)
                                    • {{ $request->property->title }}
                                @endif
                                · {{ $request->created_at->diffForHumans() }}
                            </p>
                            <p class="text-xs text-gray-400">Status: {{ ucfirst($request->status) }}</p>
                        </div>

                        @php
                            $payload = $request->payload ?? [];
                            $fieldLabels = [
                                'title' => 'Title',
                                'type' => 'Type',
                                'address_line' => 'Address',
                                'city' => 'City',
                                'state' => 'State',
                                'postal_code' => 'Postal Code',
                                'area_sqft' => 'Area (sq.ft)',
                                'description' => 'Description',
                                'is_available_for_rent' => 'Rentable',
                                'rent_price' => 'Rent Price',
                            ];
                            $formatValue = function ($field, $value) {
                                if (is_bool($value)) {
                                    return $value ? 'Yes' : 'No';
                                }
                                if (is_null($value) || $value === '') {
                                    return '—';
                                }
                                if ($field === 'area_sqft') {
                                    return number_format((float) $value, 2) . ' sq.ft';
                                }
                                if ($field === 'rent_price') {
                                    return 'BDT ' . number_format((float) $value, 2);
                                }
                                return $value;
                            };
                        @endphp

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2 text-sm">
                            <p class="text-xs font-semibold text-gray-500 tracking-wide uppercase">Request Details</p>

                            @if($request->type === 'add')
                                <p class="text-gray-600">New property that will be attached to {{ $request->user->display_name }} once approved:</p>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($fieldLabels as $field => $label)
                                        @if(array_key_exists($field, $payload))
                                            <div>
                                                <dt class="text-xs text-gray-500">{{ $label }}</dt>
                                                <dd class="font-medium text-gray-900">{{ $formatValue($field, $payload[$field]) }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            @elseif($request->type === 'update' && $request->property)
                                <p class="text-gray-600">Updates requested for <span class="font-semibold">{{ $request->property->title }}</span>:</p>
                                <div class="space-y-2">
                                    @foreach($fieldLabels as $field => $label)
                                        @if(array_key_exists($field, $payload))
                                            @php
                                                $currentValue = $request->property->{$field} ?? null;
                                                $proposedValue = $payload[$field];
                                            @endphp
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs">
                                                <span class="text-gray-500">{{ $label }}</span>
                                                <span class="text-gray-400">Current: <span class="text-gray-700">{{ $formatValue($field, $currentValue) }}</span></span>
                                                <span class="text-gray-500">→ Proposed: <span class="text-gray-900 font-semibold">{{ $formatValue($field, $proposedValue) }}</span></span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($request->type === 'transfer' && $request->property)
                                <p class="text-gray-600">Transfer request for <span class="font-semibold">{{ $request->property->title }}</span></p>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <div>
                                        <dt class="text-xs text-gray-500">Current Owner</dt>
                                        <dd class="font-medium text-gray-900">{{ optional($request->property->owner)->display_name ?? 'Unknown' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500">Target Email</dt>
                                        <dd class="font-medium text-gray-900">{{ $payload['target_email'] ?? '—' }}</dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs text-gray-500">Citizen Note</dt>
                                        <dd class="font-medium text-gray-900">{{ $payload['note'] ?? 'No note provided' }}</dd>
                                    </div>
                                </dl>
                            @else
                                <p class="text-gray-500">No additional information supplied.</p>
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.properties.requests.handle', $request) }}" class="flex flex-col md:flex-row gap-2">
                        @csrf
                        <input type="text" name="decision_note" placeholder="Decision note" class="border rounded p-2">
                        <div class="flex gap-2">
                            <button name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded">Approve</button>
                            <button name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
                        </div>
                    </form>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-gray-500">No requests found.</p>
            @endforelse
        </div>
        <div class="px-6 py-4">{{ $requests->links() }}</div>
    </div>
</div>
@endsection
