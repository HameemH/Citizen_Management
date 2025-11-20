<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" name="title" value="{{ old('title', optional($property)->title) }}" class="mt-1 w-full border rounded" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Type</label>
        <select name="type" class="mt-1 w-full border rounded" required>
            @foreach(['residential','commercial'] as $type)
                <option value="{{ $type }}" @selected(old('type', optional($property)->type) === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Owner</label>
    <select name="owner_id" class="mt-1 w-full border rounded">
        <option value="">Unassigned</option>
        @foreach($citizens as $citizen)
            <option value="{{ $citizen->id }}" @selected(old('owner_id', optional($property)->owner_id) == $citizen->id)>{{ $citizen->display_name }} ({{ $citizen->email }})</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Address</label>
    <input type="text" name="address_line" value="{{ old('address_line', optional($property)->address_line) }}" class="mt-1 w-full border rounded">
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <input type="text" name="city" value="{{ old('city', optional($property)->city) }}" placeholder="City" class="border rounded p-2">
    <input type="text" name="state" value="{{ old('state', optional($property)->state) }}" placeholder="State" class="border rounded p-2">
    <input type="text" name="postal_code" value="{{ old('postal_code', optional($property)->postal_code) }}" placeholder="Postal" class="border rounded p-2">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" rows="3" class="mt-1 w-full border rounded">{{ old('description', optional($property)->description) }}</textarea>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Area (sqft)</label>
        <input type="number" step="0.01" name="area_sqft" value="{{ old('area_sqft', optional($property)->area_sqft) }}" class="mt-1 w-full border rounded">
    </div>
    <div class="flex items-center space-x-2">
        <label class="inline-flex items-center mt-6">
            <input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', optional($property)->is_active ?? true) ? 'checked' : '' }}>
            <span class="ml-2 text-sm text-gray-700">Active</span>
        </label>
    </div>
    <div class="flex items-center space-x-2">
        <label class="inline-flex items-center mt-6">
            <input type="checkbox" name="is_available_for_rent" value="1" class="rounded" {{ old('is_available_for_rent', optional($property)->is_available_for_rent) ? 'checked' : '' }}>
            <span class="ml-2 text-sm text-gray-700">Rentable</span>
        </label>
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Rent price</label>
    <input type="number" step="0.01" name="rent_price" value="{{ old('rent_price', optional($property)->rent_price) }}" class="mt-1 w-full border rounded">
</div>
