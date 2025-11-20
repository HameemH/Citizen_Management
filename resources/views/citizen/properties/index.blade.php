@extends('layouts.citizen')

@section('title', 'Properties')
@section('page-title', 'Community Properties')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="mt-1 w-full rounded-md border-gray-300" placeholder="Title or city">
            </div>
            <div class="flex items-end">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="rent_only" value="1" class="rounded" {{ request('rent_only') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Rent availability</span>
                </label>
            </div>
            <div class="md:col-span-2 flex items-end justify-end gap-3">
                <a href="{{ route('citizen.properties.request.add') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Request new property</a>
                <button type="submit" class="px-4 py-2 border rounded text-gray-700">Filter</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($properties as $property)
            <div class="bg-white shadow rounded-lg p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $property->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $property->address_line }}, {{ $property->city }}</p>
                    <p class="mt-3 text-sm text-gray-600">{{ Str::limit($property->description, 120) }}</p>
                </div>
                <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                    <span>Owner: {{ optional($property->owner)->display_name ?? 'Unassigned' }}</span>
                    <a href="{{ route('citizen.properties.show', $property) }}" class="text-green-600 font-medium">View</a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No properties found.</p>
        @endforelse
    </div>

    <div>
        {{ $properties->links() }}
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-lg font-semibold">Recent Requests</h4>
        <ul class="mt-4 divide-y">
            @forelse($userRequests as $request)
                <li class="py-3 flex justify-between text-sm">
                    <div>
                        <p class="font-medium capitalize">{{ $request->type }} request</p>
                        <p class="text-gray-500">Status: {{ $request->status }}</p>
                    </div>
                    <span class="text-gray-400">{{ $request->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <p class="text-gray-500">No recent requests.</p>
            @endforelse
        </ul>
    </div>
</div>
@endsection
