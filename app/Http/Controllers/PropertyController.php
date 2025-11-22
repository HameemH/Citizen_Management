<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyRequest;
use App\Models\RentAgreement;
use App\Models\RentalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $ownedProperties = Property::query()
            ->where('owner_id', Auth::id())
            ->withCount([
                'rentalRequests as pending_rental_requests_count' => fn ($query) => $query->where('status', 'pending'),
                'rentAgreements as active_rental_count' => fn ($query) => $query->whereDate('end_date', '>=', now()),
            ])
            ->latest()
            ->get();

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

        $userRequests = PropertyRequest::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();
        $userRentalRequests = RentalRequest::with(['property.owner'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $activeRentals = RentAgreement::with(['property.owner'])
            ->where('tenant_id', Auth::id())
            ->whereDate('end_date', '>=', now())
            ->orderByDesc('start_date')
            ->take(5)
            ->get();

        return view('citizen.properties.index', compact('properties', 'ownedProperties', 'userRequests', 'userRentalRequests', 'activeRentals'));
    }

    public function show(Property $property)
    {
        $canViewValuation = $property->owner_id === Auth::id();

        $pendingRentalRequests = collect();
        $activeAgreement = null;
        if ($canViewValuation) {
            $pendingRentalRequests = RentalRequest::with('user')
                ->where('property_id', $property->id)
                ->where('status', 'pending')
                ->latest()
                ->get();
        }

        $activeAgreement = $property->rentAgreements()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->latest('start_date')
            ->first();

        return view('citizen.properties.show', [
            'property' => $property,
            'canViewValuation' => $canViewValuation,
            'pendingRentalRequests' => $pendingRentalRequests,
            'activeAgreement' => $activeAgreement,
        ]);
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
            'assessed_value' => 'nullable|numeric|min:0',
            'land_use' => 'nullable|string|max:50',
            'last_valuation_at' => 'nullable|date',
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
            'assessed_value' => 'nullable|numeric|min:0',
            'land_use' => 'nullable|string|max:50',
            'last_valuation_at' => 'nullable|date',
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
        abort_if($property->owner_id === Auth::id(), 403);

        $hasActiveAgreement = $property->rentAgreements()
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->exists();

        if ($hasActiveAgreement) {
            return back()->withErrors([
                'message' => 'This property already has an active rent agreement. Please wait until it ends before submitting a new rental request.',
            ]);
        }

        $hasExistingRequest = RentalRequest::where('property_id', $property->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($hasExistingRequest) {
            return back()->withErrors([
                'message' => 'You have already submitted a rental request for this property. Duplicate submissions are not allowed.',
            ]);
        }

        $data = $request->validate([
            'message' => 'nullable|string|max:500',
            'tenant_start_date' => 'required|date|after_or_equal:today',
            'tenant_end_date' => 'required|date|after:tenant_start_date',
            'tenant_monthly_rent' => 'nullable|numeric|min:0',
            'tenant_security_deposit' => 'nullable|numeric|min:0',
        ]);

        $ownerDefinedRent = $property->rent_price;
        $tenantMonthlyRent = $ownerDefinedRent ?? $data['tenant_monthly_rent'] ?? null;

        if (is_null($tenantMonthlyRent)) {
            return back()->withErrors([
                'tenant_monthly_rent' => 'Monthly rent is not set for this property yet. Contact the owner to update their rental information.',
            ])->withInput();
        }

        RentalRequest::create([
            'property_id' => $property->id,
            'user_id' => Auth::id(),
            'message' => $data['message'] ?? null,
            'tenant_start_date' => $data['tenant_start_date'],
            'tenant_end_date' => $data['tenant_end_date'],
            'tenant_monthly_rent' => $tenantMonthlyRent,
            'tenant_security_deposit' => $data['tenant_security_deposit'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Rental request sent to property owner.');
    }

    public function confirmRentalRequest(Request $request, RentalRequest $rentalRequest)
    {
        abort_unless($rentalRequest->property->owner_id === Auth::id(), 403);
        abort_if($rentalRequest->status !== 'pending', 403);

        $data = $request->validate([
            'owner_start_date' => 'required|date|after_or_equal:today',
            'owner_end_date' => 'required|date|after:owner_start_date',
            'owner_monthly_rent' => 'required|numeric|min:0',
            'owner_security_deposit' => 'nullable|numeric|min:0',
            'owner_notes' => 'nullable|string|max:500',
        ]);

        $rentalRequest->fill([
            'owner_start_date' => $data['owner_start_date'],
            'owner_end_date' => $data['owner_end_date'],
            'owner_monthly_rent' => $data['owner_monthly_rent'],
            'owner_security_deposit' => $data['owner_security_deposit'] ?? null,
            'owner_notes' => $data['owner_notes'] ?? null,
            'owner_confirmed_at' => now(),
            'owner_confirmed_by' => Auth::id(),
            'ready_for_admin' => true,
        ])->save();

        return back()->with('status', 'Rental request forwarded to municipal approval.');
    }

    protected function authorizeOwner(Property $property): void
    {
        abort_unless($property->owner_id === Auth::id(), 403);
    }
}