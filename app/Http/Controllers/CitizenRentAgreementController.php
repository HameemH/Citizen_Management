<?php

namespace App\Http\Controllers;

use App\Models\RentAgreement;
use Illuminate\Support\Facades\Auth;

class CitizenRentAgreementController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $agreements = RentAgreement::with('property')
            ->where(function ($query) use ($userId) {
                $query->where('tenant_id', $userId)
                    ->orWhere('landlord_id', $userId);
            })
            ->latest('start_date')
            ->paginate(10);

        return view('citizen.rent-agreements.index', compact('agreements'));
    }

    public function show(RentAgreement $rentAgreement)
    {
        abort_unless(in_array(Auth::id(), [$rentAgreement->tenant_id, $rentAgreement->landlord_id]), 403);

        $rentAgreement->load(['property', 'landlord', 'tenant']);

        return view('citizen.rent-agreements.show', compact('rentAgreement'));
    }
}
