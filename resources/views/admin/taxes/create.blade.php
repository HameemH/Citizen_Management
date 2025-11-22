@extends('layouts.admin')

@section('title', 'Generate Tax Assessment')
@section('page-title', 'Generate Tax Assessment')

@section('content')
<div class="max-w-4xl bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('admin.taxes.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Property</label>
            <select name="property_id" class="mt-1 w-full border rounded" required>
                <option value="">Select property</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>
                        {{ $property->title }} — {{ optional($property->owner)->display_name ?? 'Unassigned' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Fiscal Year</label>
                <input type="text" name="fiscal_year" value="{{ old('fiscal_year', $defaultYear) }}" class="mt-1 w-full border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Assessed Value Override (BDT)</label>
                <input type="number" step="0.01" name="assessed_value" value="{{ old('assessed_value') }}" class="mt-1 w-full border rounded" placeholder="Optional">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Note</label>
            <textarea name="note" rows="3" class="mt-1 w-full border rounded" placeholder="Optional notes"></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.taxes.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">Generate</button>
        </div>
    </form>
</div>
@endsection
