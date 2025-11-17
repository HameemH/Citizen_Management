@extends('layouts.admin')

@section('title', 'Review Verification')
@section('page-title', 'Review Verification Request')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;

    $documentUrl = function (?string $path) {
        return $path ? Storage::url($path) : null;
    };
@endphp
<div class="space-y-6">
    <!-- Applicant Summary -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Applicant Information</h3>
                <p class="text-sm text-gray-600">Submitted on {{ optional($user->verification_requested_at)->format('M d, Y h:i A') ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ ucfirst($user->verification_status) }}
                </span>
            </div>
        </div>
        <div class="px-6 py-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Citizen Profile</h4>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Full Name</dt>
                        <dd class="text-sm text-gray-900">{{ $user->full_name ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $user->phone_number ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">NID Number</dt>
                        <dd class="text-sm text-gray-900">{{ $user->nid_number ?? 'Not provided' }}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Address</h4>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Present Address</dt>
                        <dd class="text-sm text-gray-900">{{ $user->present_address ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Permanent Address</dt>
                        <dd class="text-sm text-gray-900">{{ $user->permanent_address ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Verification Notes</dt>
                        <dd class="text-sm text-gray-900">{{ $user->verification_notes ?? '—' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Registry Comparison -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Cross-check with Central Registry</h3>
            <p class="text-sm text-gray-600">Ensure the submitted information matches the Fake NID dataset.</p>
        </div>
        <div class="p-6 overflow-x-auto">
            @if($nidRecord)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Citizen Submission</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registry Record</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">Full Name</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $user->full_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $nidRecord->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">Date of Birth</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ optional($user->date_of_birth)->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ optional($nidRecord->date_of_birth)->format('M d, Y') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">Father's Name</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $user->father_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $nidRecord->father_name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">Mother's Name</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $user->mother_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $nidRecord->mother_name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">Permanent Address</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $user->permanent_address ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $nidRecord->permanent_address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">Present Address</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $user->present_address ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $nidRecord->present_address ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="text-sm text-red-600">No matching Fake NID record was found for this application.</div>
            @endif
        </div>
    </div>

    <!-- Document Review -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Submitted Documents</h3>
                <p class="text-sm text-gray-600">Preview the citizen's uploaded files.</p>
            </div>
            <div class="text-sm text-gray-500">Files stored in <code>storage/app/public</code></div>
        </div>
        <div class="p-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            @php
                $documents = [
                    ['label' => 'NID - Front', 'path' => $user->nid_front_image],
                    ['label' => 'NID - Back', 'path' => $user->nid_back_image],
                    ['label' => 'Passport Photo', 'path' => $user->passport_photo],
                ];
            @endphp
            @foreach($documents as $document)
                <div class="border rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 text-sm font-medium text-gray-700">
                        {{ $document['label'] }}
                    </div>
                    <div class="p-4 bg-gray-100 flex items-center justify-center min-h-[200px]">
                        @php $url = $documentUrl($document['path']); @endphp
                        @if($url)
                            <img src="{{ $url }}" alt="{{ $document['label'] }}" class="max-h-56 rounded shadow">
                        @else
                            <p class="text-sm text-gray-500">No file uploaded</p>
                        @endif
                    </div>
                    @if($url)
                        <div class="px-4 py-2 bg-white border-t border-gray-100 text-right">
                            <a href="{{ $url }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Open full size</a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Decision Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Verification Decision</h3>
                <p class="text-sm text-gray-600">Approve once everything checks out, or provide a rejection reason.</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">
                <form action="{{ route('admin.verification.approve', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-5 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Approve Verification
                    </button>
                </form>
                <form action="{{ route('admin.verification.reject', $user) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <input type="text" name="rejection_reason" placeholder="Reason" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    <button type="submit" class="inline-flex items-center px-5 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
