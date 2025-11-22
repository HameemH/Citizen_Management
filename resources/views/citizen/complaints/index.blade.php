@extends('layouts.citizen')

@section('title', 'Complaints')
@section('page-title', 'My Complaints')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('status') }}</div>
    @endif

    <div class="flex justify-end">
        <a href="{{ route('citizen.complaints.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded shadow">File Complaint</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Property</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Updated</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($complaints as $complaint)
                    <tr>
                        <td class="px-4 py-3 text-gray-900 font-medium">{{ $complaint->subject }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $complaint->category ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $complaint->property?->title ?? 'General' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold @class([
                                'bg-yellow-100 text-yellow-800' => $complaint->status === 'open',
                                'bg-blue-100 text-blue-800' => $complaint->status === 'in_progress',
                                'bg-green-100 text-green-800' => $complaint->status === 'resolved',
                            ])">
                                {{ str_replace('_', ' ', ucfirst($complaint->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $complaint->updated_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('citizen.complaints.show', $complaint) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No complaints submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $complaints->links() }}
        </div>
    </div>
</div>
@endsection
