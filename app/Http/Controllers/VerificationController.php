<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\FakeNid;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('citizen')->except(['adminIndex', 'approve', 'reject']);
        $this->middleware('admin')->only(['adminIndex', 'approve', 'reject']);
    }

    /**
     * Show the verification application form
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user already has a verification request pending or approved
        if ($user->verification_status === 'verified') {
            return redirect()->route('citizen.dashboard')->with('info', 'You are already verified!');
        }

        if ($user->verification_status === 'pending' && $user->verification_requested_at) {
            return redirect()->route('citizen.dashboard')->with('info', 'Your verification request is already pending review.');
        }

        return view('verification.create');
    }

    /**
     * Store the verification application
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate the form data
        $validated = $request->validate([
            'nid_number' => 'required|string|size:10|regex:/^[0-9]+$/',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:500',
            'present_address' => 'required|string|max:500',
            'phone_number' => 'required|string|size:11|regex:/^[0-9]+$/',
            'email' => 'required|email|max:255',
            'nid_front_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nid_back_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Check if NID number exists in our fake database
        $nidRecord = FakeNid::where('nid_number', $validated['nid_number'])->first();
        
        if (!$nidRecord) {
            return back()->withErrors(['nid_number' => 'This NID number is not found in our database.'])->withInput();
        }

        if ($nidRecord->is_verified) {
            return back()->withErrors(['nid_number' => 'This NID is already verified by another user.'])->withInput();
        }

        if ($nidRecord->is_blocked) {
            return back()->withErrors(['nid_number' => 'This NID has been blocked for security reasons.'])->withInput();
        }

        // Verify basic information matches
        if (strtolower(trim($nidRecord->name)) !== strtolower(trim($validated['full_name']))) {
            return back()->withErrors(['full_name' => 'Full name does not match our NID records.'])->withInput();
        }

        if ($nidRecord->date_of_birth !== $validated['date_of_birth']) {
            return back()->withErrors(['date_of_birth' => 'Date of birth does not match our NID records.'])->withInput();
        }

        // Store uploaded files
        $nidFrontPath = $request->file('nid_front_image')->store('verification/nid_front', 'public');
        $nidBackPath = $request->file('nid_back_image')->store('verification/nid_back', 'public');
        $passportPhotoPath = $request->file('passport_photo')->store('verification/passport_photos', 'public');

        // Update user with verification data
        $user->update([
            'nid_number' => $validated['nid_number'],
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'father_name' => $validated['father_name'],
            'mother_name' => $validated['mother_name'],
            'permanent_address' => $validated['permanent_address'],
            'present_address' => $validated['present_address'],
            'phone_number' => $validated['phone_number'],
            'verification_status' => 'pending',
            'nid_front_image' => $nidFrontPath,
            'nid_back_image' => $nidBackPath,
            'passport_photo' => $passportPhotoPath,
            'verification_requested_at' => now(),
        ]);

        return redirect()->route('citizen.dashboard')->with('success', 'Verification application submitted successfully! Your request is now pending review.');
    }

    /**
     * Admin view to manage verification requests
     */
    public function adminIndex()
    {
        $pendingRequests = User::where('verification_status', 'pending')
            ->whereNotNull('verification_requested_at')
            ->orderBy('verification_requested_at', 'asc')
            ->get();

        $verifiedUsers = User::where('verification_status', 'verified')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.verification.index', compact('pendingRequests', 'verifiedUsers'));
    }

    /**
     * Show verification request details for admin
     */
    public function show(User $user)
    {
        if ($user->verification_status !== 'pending') {
            return redirect()->route('admin.verification.index')->with('error', 'This user does not have a pending verification request.');
        }

        $nidRecord = FakeNid::where('nid_number', $user->nid_number)->first();

        return view('admin.verification.show', compact('user', 'nidRecord'));
    }

    /**
     * Approve verification request
     */
    public function approve(User $user)
    {
        if ($user->verification_status !== 'pending') {
            return redirect()->route('admin.verification.index')->with('error', 'This user does not have a pending verification request.');
        }

        // Mark the NID as verified in the fake_nids table
        FakeNid::where('nid_number', $user->nid_number)->update(['is_verified' => true]);

        // Update user status
        $user->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()->route('admin.verification.index')->with('success', 'User verification approved successfully!');
    }

    /**
     * Reject verification request
     */
    public function reject(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($user->verification_status !== 'pending') {
            return redirect()->route('admin.verification.index')->with('error', 'This user does not have a pending verification request.');
        }

        // Update user status
        $user->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        // Clean up uploaded files
        if ($user->nid_front_image) Storage::disk('public')->delete($user->nid_front_image);
        if ($user->nid_back_image) Storage::disk('public')->delete($user->nid_back_image);
        if ($user->passport_photo) Storage::disk('public')->delete($user->passport_photo);

        // Clear verification data
        $user->update([
            'nid_front_image' => null,
            'nid_back_image' => null,
            'passport_photo' => null,
        ]);

        return redirect()->route('admin.verification.index')->with('success', 'Verification request rejected.');
    }
}