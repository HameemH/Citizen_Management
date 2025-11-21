@extends('layouts.citizen')

@section('title', 'Request Property Addition')
@section('page-title', 'Request New Property')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('citizen.properties.request.add.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" class="mt-1 w-full border rounded" required>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" class="mt-1 w-full border rounded" required>
                    <option value="residential">Residential</option>
                    <option value="commercial">Commercial</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Area (sqft)</label>
                <input type="number" step="0.01" name="area_sqft" class="mt-1 w-full border rounded">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" name="address_line" class="mt-1 w-full border rounded">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="city" placeholder="City" class="border rounded p-2">
            <input type="text" name="state" placeholder="State" class="border rounded p-2">
            <input type="text" name="postal_code" placeholder="Postal Code" class="border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 w-full border rounded"></textarea>
        </div>
        <div class="flex items-center space-x-3">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_available_for_rent" value="1" class="rounded">
                <span class="ml-2 text-sm text-gray-700">Available for rent</span>
            </label>
            <input type="number" step="0.01" name="rent_price" placeholder="Rent price" class="border rounded p-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Assessed Value (BDT)</label>
                <input type="number" step="0.01" name="assessed_value" class="mt-1 w-full border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Land Use</label>
                <input type="text" name="land_use" class="mt-1 w-full border rounded" placeholder="Residential, Commercial...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Valuation Date</label>
                <input type="date" name="last_valuation_at" class="mt-1 w-full border rounded">
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('citizen.properties.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-green-600 text-white rounded">Submit Request</button>
        </div>
    </form>
</div>
@endsection
