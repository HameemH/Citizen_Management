@extends('layouts.citizen')

@section('title', $complaint->subject)
@section('page-title', 'Complaint Details')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Subject</p>
                <p class="text-2xl font-bold text-gray-900">{{ $complaint->subject }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold @class([
                'bg-yellow-100 text-yellow-800' => $complaint->status === 'open',
                'bg-blue-100 text-blue-800' => $complaint->status === 'in_progress',
                'bg-green-100 text-green-800' => $complaint->status === 'resolved',
            ])">
                {{ str_replace('_', ' ', ucfirst($complaint->status)) }}
            </span>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <p class="text-sm text-gray-500">Category</p>
                <p class="text-lg font-semibold text-gray-900">{{ $complaint->category ?? 'General' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Property</p>
                <p class="text-lg font-semibold text-gray-900">{{ $complaint->property?->title ?? 'General' }}</p>
            </div>
        </div>

        <div>
            <p class="text-sm text-gray-500">Description</p>
            <div class="mt-2 rounded border border-gray-200 bg-gray-50 p-4 text-gray-700 whitespace-pre-line">
                {{ $complaint->description }}
            </div>
        </div>

        @if($complaint->attachment_path)
            <div>
                <p class="text-sm text-gray-500">Attachment</p>
                <a href="{{ asset('storage/' . $complaint->attachment_path) }}" class="text-indigo-600 hover:text-indigo-800" target="_blank">View attachment</a>
            </div>
        @endif

        <div>
            <p class="text-sm text-gray-500">Submitted</p>
            <p class="text-lg text-gray-700">{{ $complaint->created_at->format('M d, Y h:i A') }}</p>
        </div>

        @if($complaint->admin_reply)
            <div class="border rounded-lg p-4 bg-green-50 border-green-200">
                <p class="text-sm font-semibold text-green-900">Admin Response</p>
                <p class="text-sm text-green-700 whitespace-pre-line mt-2">{{ $complaint->admin_reply }}</p>
            </div>
        @endif
    </div>

    <a href="{{ route('citizen.complaints.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded shadow-sm hover:bg-gray-50">Back to list</a>
</div>
@endsection
