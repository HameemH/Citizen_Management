@extends('layouts.admin')

@section('title', 'Agreement ' . $rentAgreement->agreement_number)
@section('page-title', 'Rent Agreement Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Agreement #</p>
                <p class="text-2xl font-bold text-gray-900">{{ $rentAgreement->agreement_number }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $rentAgreement->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($rentAgreement->status) }}
            </span>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <p class="text-sm text-gray-500">Property</p>
                <p class="text-lg font-semibold text-gray-900">{{ $rentAgreement->property?->title }}</p>
                <p class="text-sm text-gray-500">{{ $rentAgreement->property?->address_line }}, {{ $rentAgreement->property?->city }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tenant</p>
                <p class="text-lg font-semibold text-gray-900">{{ $rentAgreement->tenant?->display_name }}</p>
                <p class="text-sm text-gray-500">{{ $rentAgreement->tenant?->email }}</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500">Start Date</p>
                <p class="text-lg font-semibold text-gray-900">{{ $rentAgreement->start_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">End Date</p>
                <p class="text-lg font-semibold text-gray-900">{{ $rentAgreement->end_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Monthly Rent</p>
                <p class="text-lg font-semibold text-gray-900">BDT {{ number_format($rentAgreement->monthly_rent, 2) }}</p>
            </div>
        </div>

        <div>
            <p class="text-sm text-gray-500">Security Deposit</p>
            <p class="text-lg font-semibold text-gray-900">BDT {{ number_format($rentAgreement->security_deposit, 2) }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Agreement Terms</p>
            <div class="mt-2 rounded border border-gray-200 bg-gray-50 p-4 text-gray-700 whitespace-pre-line">
                {{ $rentAgreement->terms_text }}
            </div>
        </div>
    </div>

    <a href="{{ route('admin.rent-agreements.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded shadow-sm hover:bg-gray-50">Back to list</a>
</div>
@endsection
