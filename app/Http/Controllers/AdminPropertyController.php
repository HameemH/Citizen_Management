<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyRequest;
use App\Models\RentalRequest;
use App\Models\User;
use App\Notifications\PropertyTransferCompleted;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::query()
            ->when($request->filled('city'), fn($query) => $query->where('city', $request->city))
            ->when($request->filled('owner'), function ($query) use ($request) {
                $query->whereHas('owner', fn($q) => $q->where('display_name', 'like', '%' . $request->owner . '%'));
            })
            ->paginate(15)
            ->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $citizens = User::where('role', 'citizen')->orderBy('display_name')->get();

        return view('admin.properties.create', compact('citizens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'owner_id' => 'nullable|exists:users,id',
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

        Property::create($data);

        return redirect()->route('admin.properties.index')->with('status', 'Property created.');
    }

    public function edit(Property $property)
    {
        $citizens = User::where('role', 'citizen')->orderBy('display_name')->get();

        return view('admin.properties.edit', compact('property', 'citizens'));
    }

    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'owner_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'address_line' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'area_sqft' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_available_for_rent' => 'nullable|boolean',
            'rent_price' => 'nullable|numeric|min:0',
            'assessed_value' => 'nullable|numeric|min:0',
            'land_use' => 'nullable|string|max:50',
            'last_valuation_at' => 'nullable|date',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_available_for_rent'] = $request->boolean('is_available_for_rent');

        $property->update($data);

        return redirect()->route('admin.properties.index')->with('status', 'Property updated.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return back()->with('status', 'Property removed.');
    }

    public function requests()
    {
        $requests = PropertyRequest::with(['user', 'property.owner'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.properties.requests', compact('requests'));
    }

    public function handleRequest(Request $request, PropertyRequest $propertyRequest)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'decision_note' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($propertyRequest, $validated) {
            $propertyRequest->update([
                'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
                'decision_note' => $validated['decision_note'] ?? null,
                'decided_by' => auth()->id(),
                'decided_at' => Carbon::now(),
            ]);

            if ($validated['action'] === 'approve') {
                $this->applyRequestPayload($propertyRequest);
            }
        });

        return back()->with('status', 'Request processed.');
    }

    public function rentalRequests()
    {
        $requests = RentalRequest::with(['property', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.properties.rentals', compact('requests'));
    }

    public function handleRental(Request $request, RentalRequest $rentalRequest)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'decision_note' => 'nullable|string|max:500',
        ]);

        $rentalRequest->update([
            'status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'decision_note' => $validated['decision_note'] ?? null,
            'decided_by' => auth()->id(),
            'decided_at' => Carbon::now(),
        ]);

        return back()->with('status', 'Rental request updated.');
    }

    protected function applyRequestPayload(PropertyRequest $request): void
    {
        $data = $request->payload;

        if ($request->type === 'add') {
            Property::create(array_merge($data, [
                'owner_id' => $request->user_id,
            ]));
            return;
        }

        if (!$request->property) {
            return;
        }

        if ($request->type === 'update') {
            $request->property->update($data);
            return;
        }

        if ($request->type === 'transfer') {
            $target = User::where('email', $data['target_email'] ?? null)->first();
            if ($target) {
                $previousOwner = $request->property->owner;
                $request->property->update(['owner_id' => $target->id]);

                $this->notifyTransferCompleted($request, $target, $previousOwner);
            }
        }
    }

    protected function notifyTransferCompleted(PropertyRequest $propertyRequest, User $newOwner, ?User $previousOwner): void
    {
        $property = $propertyRequest->property->fresh(['owner']);
        $recipients = collect([$newOwner, $propertyRequest->user])
            ->filter()
            ->unique(fn ($user) => $user->id)
            ->all();

        if (empty($recipients)) {
            return;
        }

        Notification::send($recipients, new PropertyTransferCompleted($property, $previousOwner));
    }
}