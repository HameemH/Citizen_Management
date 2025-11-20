@extends('layouts.admin')

@section('title', 'Edit Property')
@section('page-title', 'Edit Property')

@section('content')
<div class="max-w-4xl bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('admin.properties.update', $property) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('admin.properties.partials.form', ['property' => $property, 'citizens' => $citizens])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save Changes</button>
        </div>
    </form>
</div>
@endsection
