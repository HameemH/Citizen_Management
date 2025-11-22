<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('citizen.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $properties = Property::where('owner_id', Auth::id())
            ->orderBy('title')
            ->get();

        return view('citizen.complaints.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'property_id' => 'nullable|exists:properties,id',
            'attachment' => 'nullable|file|max:4096',
        ]);

        if (!empty($data['property_id'])) {
            $ownsProperty = Property::where('id', $data['property_id'])
                ->where('owner_id', Auth::id())
                ->exists();

            abort_unless($ownsProperty, 403);
        }

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('complaints', 'public');
        }

        $data['user_id'] = Auth::id();

        Complaint::create($data);

        return redirect()->route('citizen.complaints.index')->with('status', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint)
    {
        abort_unless($complaint->user_id === Auth::id(), 403);

        $complaint->load(['property']);

        return view('citizen.complaints.show', compact('complaint'));
    }
}
