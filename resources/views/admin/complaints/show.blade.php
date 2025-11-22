@extends('layouts.admin')

@section('title', $complaint->subject)
@section('page-title', 'Complaint Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Subject</p>
                <p class="text-2xl font-bold text-gray-900">{{ $complaint->subject }}</p>
                <p class="text-sm text-gray-500">Filed by {{ $complaint->user->display_name }} ({{ $complaint->user->email }})</p>
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

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <p class="text-sm text-gray-500">Submitted</p>
                <p class="text-lg text-gray-700">{{ $complaint->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Last updated</p>
                <p class="text-lg text-gray-700">{{ $complaint->updated_at->diffForHumans() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Resolved</p>
                <p class="text-lg text-gray-700">
                    @if($complaint->resolved_at)
                        {{ $complaint->resolved_at->format('M d, Y h:i A') }} by {{ optional($complaint->resolver)->display_name ?? 'System' }}
                    @else
                        Not resolved
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="open" @selected($complaint->status === 'open')>Open</option>
                    <option value="in_progress" @selected($complaint->status === 'in_progress')>In progress</option>
                    <option value="resolved" @selected($complaint->status === 'resolved')>Resolved</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Admin response</label>
                <textarea name="admin_reply" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Provide guidance or resolution details">{{ old('admin_reply', $complaint->admin_reply) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.complaints.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-semibold rounded-md text-gray-700 bg-white hover:bg-gray-50">Back</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Update Complaint</button>
            </div>
        </form>
    </div>
</div>
@endsection
