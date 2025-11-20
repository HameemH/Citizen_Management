@extends('layouts.citizen')

@section('title', 'Request Transfer')
@section('page-title', 'Transfer Ownership')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">{{ $property->title }}</h2>
    <form method="POST" action="{{ route('citizen.properties.request.transfer.store', $property) }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Recipient email</label>
            <input type="email" name="target_email" class="mt-1 w-full border rounded" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Note (optional)</label>
            <textarea name="note" rows="3" class="mt-1 w-full border rounded"></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('citizen.properties.show', $property) }}" class="px-4 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-yellow-500 text-white rounded">Send Request</button>
        </div>
    </form>
</div>
@endsection
