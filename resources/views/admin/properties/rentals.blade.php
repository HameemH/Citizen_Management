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
                <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $request->property->title }}</p>
                        <p class="text-sm text-gray-500">Requested by {{ $request->user->display_name }} · {{ $request->created_at->diffForHumans() }}</p>
                        <p class="text-sm text-gray-600">{{ $request->message ?? 'No message provided.' }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.rental-requests.handle', $request) }}" class="flex flex-col md:flex-row gap-2">
                        @csrf
                        <input type="text" name="decision_note" placeholder="Decision note" class="border rounded p-2">
                        <div class="flex gap-2">
                            <button name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded">Approve</button>
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
