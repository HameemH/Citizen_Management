@extends('layouts.admin')

@section('title', 'Manage Properties')
@section('page-title', 'Property Management')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">All Properties</h2>
            <div class="space-x-3">
                <a href="{{ route('admin.properties.requests') }}" class="px-4 py-2 border rounded">Requests</a>
                <a href="{{ route('admin.properties.rentals') }}" class="px-4 py-2 border rounded">Rental Requests</a>
                <a href="{{ route('admin.properties.create') }}" class="px-4 py-2 bg-green-600 text-white rounded">Add Property</a>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <input type="text" name="owner" value="{{ request('owner') }}" placeholder="Owner name" class="border rounded p-2">
            <input type="text" name="city" value="{{ request('city') }}" placeholder="City" class="border rounded p-2">
            <button class="px-4 py-2 border rounded">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Owner</th>
                        <th class="px-4 py-2">City</th>
                        <th class="px-4 py-2">Rent</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($properties as $property)
                        <tr>
                            <td class="px-4 py-2">
                                <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                <p class="text-sm text-gray-500">{{ $property->type }}</p>
                            </td>
                            <td class="px-4 py-2">{{ optional($property->owner)->display_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $property->city }}</td>
                            <td class="px-4 py-2">{{ $property->is_available_for_rent ? '৳' . number_format($property->rent_price, 0) : 'Not listed' }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('admin.properties.edit', $property) }}" class="text-blue-600">Edit</a>
                                <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600" onclick="return confirm('Remove property?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">No properties found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $properties->links() }}</div>
    </div>
</div>
@endsection
