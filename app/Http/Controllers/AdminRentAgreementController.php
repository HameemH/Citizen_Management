<?php

namespace App\Http\Controllers;

use App\Models\RentAgreement;
use Illuminate\Http\Request;

class AdminRentAgreementController extends Controller
{
    public function index(Request $request)
    {
        $agreements = RentAgreement::with(['property', 'tenant'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->whereHas('property', fn ($propertyQuery) => $propertyQuery->where('title', 'like', '%' . $request->q . '%'))
                        ->orWhereHas('tenant', fn ($tenantQuery) => $tenantQuery->where('display_name', 'like', '%' . $request->q . '%'));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.rent-agreements.index', compact('agreements'));
    }

    public function show(RentAgreement $rentAgreement)
    {
        $rentAgreement->load(['property', 'tenant', 'landlord']);

        return view('admin.rent-agreements.show', compact('rentAgreement'));
    }
}
