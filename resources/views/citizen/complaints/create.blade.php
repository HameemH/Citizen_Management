@extends('layouts.citizen')

@section('title', 'File Complaint')
@section('page-title', 'File a Complaint')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <form method="POST" action="{{ route('citizen.complaints.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                @error('subject')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="e.g. Utilities, Sanitation">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Related Property (optional)</label>
                    <select name="property_id" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="">General issue</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>{{ $property->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="5" class="mt-1 w-full border rounded px-3 py-2" required>{{ old('description') }}</textarea>
                @error('description')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                <input type="file" name="attachment" class="mt-1 w-full border rounded px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">Max 4MB. Upload relevant photos or documents.</p>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('citizen.complaints.index') }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">Submit Complaint</button>
            </div>
        </form>
    </div>
</div>
@endsection
