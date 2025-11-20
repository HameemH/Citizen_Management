@extends('layouts.admin')

@section('title', 'Add Property')
@section('page-title', 'Add Property')

@section('content')
<div class="max-w-4xl bg-white shadow rounded-lg p-6">
    <form method="POST" action="{{ route('admin.properties.store') }}" class="space-y-4">
        @csrf
        @include('admin.properties.partials.form', ['property' => null, 'citizens' => $citizens])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.properties.index') }}" class="px-4 py-2 border rounded">Cancel</a>
            <button class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
@endsection
