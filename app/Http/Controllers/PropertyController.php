<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyRequest;
use App\Models\RentalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::query()
            ->active()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('title', 'like', '%' . $request->q . '%')
                        ->orWhere('city', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->boolean('rent_only'), fn($query) => $query->where('is_available_for_rent', true))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $userRequests = PropertyRequest::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('citizen.properties.index', compact('properties', 'userRequests'));
    }

    public function show(Property $property)
    {
        return view('citizen.properties.show', compact('property'));
    }

    public function createAddRequest()
    {
        return view('citizen.properties.requests.add');
    }

    public function storeAddRequest(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'area_sqft' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_available_for_rent' => 'nullable|boolean',
            'rent_price' => 'nullable|numeric|min:0',
        ]);

        $data['is_available_for_rent'] = $request->boolean('is_available_for_rent');

        PropertyRequest::create([
            'user_id' => Auth::id(),
            'type' => 'add',
            'payload' => $data,
            'status' => 'pending',
        ]);

        return redirect()->route('citizen.properties.index')->with('status', 'Property submission sent for review.');
    }

    public function createUpdateRequest(Property $property)
    {
        $this->authorizeOwner($property);

        return view('citizen.properties.requests.update', compact('property'));
    }

    public function storeUpdateRequest(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_available_for_rent' => 'nullable|boolean',
            'rent_price' => 'nullable|numeric|min:0',
        ]);

        $data['is_available_for_rent'] = $request->boolean('is_available_for_rent');

        PropertyRequest::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'type' => 'update',
            'payload' => $data,
            'status' => 'pending',
        ]);

        return redirect()->route('citizen.properties.index')->with('status', 'Update request submitted.');
    }

    public function createTransferRequest(Property $property)
    {
        $this->authorizeOwner($property);

        return view('citizen.properties.requests.transfer', compact('property'));
    }

    public function storeTransferRequest(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $data = $request->validate([
            'target_email' => 'required|email',
            'note' => 'nullable|string|max:500',
        ]);

        PropertyRequest::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'type' => 'transfer',
            'payload' => $data,
            'status' => 'pending',
        ]);

        return redirect()->route('citizen.properties.index')->with('status', 'Transfer request submitted.');
    }

    public function submitRentalRequest(Request $request, Property $property)
    {
        $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        RentalRequest::create([
            'property_id' => $property->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Rental request sent to property owner.');
    }

    protected function authorizeOwner(Property $property): void
    {
        abort_unless($property->owner_id === Auth::id(), 403);
    }
}