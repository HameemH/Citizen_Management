<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::with(['user', 'property'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($sub) use ($request) {
                    $sub->where('subject', 'like', '%' . $request->q . '%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('display_name', 'like', '%' . $request->q . '%'));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'property']);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $data = $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
            'admin_reply' => 'nullable|string',
        ]);

        $complaint->fill($data);

        if ($data['status'] === 'resolved') {
            $complaint->resolved_at = now();
            $complaint->resolved_by = auth()->id();
        }

        $complaint->save();

        return redirect()->route('admin.complaints.show', $complaint)->with('status', 'Complaint updated.');
    }
}
