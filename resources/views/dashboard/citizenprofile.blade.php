@extends('layouts.citizen')

@section('title', 'My Profile')
@section('page-title', 'Citizen Profile')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">My Profile</h3>
                    <p class="text-sm text-gray-600">Manage your personal information and verification status</p>
                </div>
                <div>
                    @if(Auth::user()->verification_status === 'verified')
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ✓ Verified
                        </span>
                    @elseif(Auth::user()->verification_status === 'pending')
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            ⏳ Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            ❌ Not Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Personal Information -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h4>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->phone ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">National ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->national_id ?? 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Account Information -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Account Information</h4>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ Auth::user()->role }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ Auth::user()->verification_status }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->created_at->format('F d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ Auth::user()->updated_at->format('F d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Edit Profile
                </button>
                @if(Auth::user()->verification_status !== 'verified')
                <button type="button" class="bg-green-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Apply for Verification
                </button>
                @endif
            </div>
        </div>
    </div>

    @if(Auth::user()->verification_status !== 'verified')
    <!-- Verification Guide -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Verification Requirements</h3>
            <p class="text-sm text-gray-600">Complete these steps to get your citizen verification</p>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full {{ Auth::user()->national_id ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                            @if(Auth::user()->national_id)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <span class="text-sm font-medium">1</span>
                            @endif
                        </span>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Provide National ID</h4>
                        <p class="text-sm text-gray-600">Submit your valid Bangladeshi National ID number for verification</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-400">
                            <span class="text-sm font-medium">2</span>
                        </span>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">NID Verification</h4>
                        <p class="text-sm text-gray-600">Your NID will be verified against the national database</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-400">
                            <span class="text-sm font-medium">3</span>
                        </span>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Admin Review</h4>
                        <p class="text-sm text-gray-600">Our administrators will review and approve your verification</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-400">
                            <span class="text-sm font-medium">4</span>
                        </span>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Access All Services</h4>
                        <p class="text-sm text-gray-600">Once verified, you can access all citizen services</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Available Services -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Available Services</h3>
            <p class="text-sm text-gray-600">Services based on your current verification status</p>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Property Management -->
                <div class="border rounded-lg p-4 {{ Auth::user()->verification_status === 'verified' ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 {{ Auth::user()->verification_status === 'verified' ? 'text-green-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium {{ Auth::user()->verification_status === 'verified' ? 'text-green-900' : 'text-gray-900' }}">Property Management</h4>
                            <p class="text-sm {{ Auth::user()->verification_status === 'verified' ? 'text-green-600' : 'text-gray-500' }}">
                                {{ Auth::user()->verification_status === 'verified' ? 'Available' : 'Requires verification' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tax Payment -->
                <div class="border rounded-lg p-4 {{ Auth::user()->verification_status === 'verified' ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 {{ Auth::user()->verification_status === 'verified' ? 'text-green-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium {{ Auth::user()->verification_status === 'verified' ? 'text-green-900' : 'text-gray-900' }}">Tax Payment</h4>
                            <p class="text-sm {{ Auth::user()->verification_status === 'verified' ? 'text-green-600' : 'text-gray-500' }}">
                                {{ Auth::user()->verification_status === 'verified' ? 'Available' : 'Requires verification' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Complaint Filing -->
                <div class="border rounded-lg p-4 border-green-200 bg-green-50">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-green-900">File Complaints</h4>
                            <p class="text-sm text-green-600">Available to all citizens</p>
                        </div>
                    </div>
                </div>

                <!-- Document Services -->
                <div class="border rounded-lg p-4 {{ Auth::user()->verification_status === 'verified' ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 {{ Auth::user()->verification_status === 'verified' ? 'text-green-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium {{ Auth::user()->verification_status === 'verified' ? 'text-green-900' : 'text-gray-900' }}">Document Services</h4>
                            <p class="text-sm {{ Auth::user()->verification_status === 'verified' ? 'text-green-600' : 'text-gray-500' }}">
                                {{ Auth::user()->verification_status === 'verified' ? 'Available' : 'Requires verification' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection