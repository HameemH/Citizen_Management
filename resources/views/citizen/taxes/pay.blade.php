@extends('layouts.citizen')

@section('title', 'Pay Assessment')
@section('page-title', 'Pay Property Tax')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Property</p>
                <h2 class="text-2xl font-semibold text-gray-900">{{ $assessment->property->title }}</h2>
                <p class="text-sm text-gray-600">Fiscal year {{ $assessment->fiscal_year }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Amount due</p>
                <p class="text-3xl font-bold text-gray-900">BDT {{ number_format($assessment->tax_amount, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Due {{ optional($assessment->due_date)->format('M d, Y') ?? '—' }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500">Status</p>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($assessment->status) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500">Assessed value snapshot</p>
                <p class="text-lg font-semibold text-gray-900">BDT {{ number_format($assessment->assessed_value_snapshot, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-500">Surface area</p>
                <p class="text-lg font-semibold text-gray-900">{{ number_format($assessment->property->area_sqft) }} sq.ft</p>
            </div>
        </div>
        @if($assessment->notes)
            <div class="bg-blue-50 border border-blue-100 text-blue-900 text-sm rounded-lg p-3">
                <p class="font-semibold">Municipal note</p>
                <p>{{ $assessment->notes }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <h3 class="text-lg font-semibold text-gray-900">Complete payment</h3>
        <p class="text-sm text-gray-600">You will be redirected to Stripe Checkout. Once the payment succeeds, you will return to the dashboard with a receipt.</p>

        @if(!$stripeEnabled)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded">
                Online payments are currently unavailable. Please try again later or visit the ward office to make a manual payment.
            </div>
        @else
            <form method="POST" action="{{ route('citizen.taxes.pay', $assessment) }}" class="space-y-3">
                @csrf
                <button class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg">
                    Proceed to Stripe Checkout
                </button>
            </form>
        @endif

        <a href="{{ route('citizen.taxes.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800">
            ← Back to my assessments
        </a>
    </div>
</div>
@endsection
