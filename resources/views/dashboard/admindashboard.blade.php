@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Welcome Message -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div class="ml-5">
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->display_name }}!</h1>
                <p class="text-gray-600">Here's what's happening with your citizen management system today.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Citizens -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Citizens</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\User::where('role', 'citizen')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Citizens -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Verified Citizens</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\User::where('role', 'citizen')->where('verification_status', 'verified')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Verifications</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\User::where('role', 'citizen')->where('verification_status', 'pending')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total NIDs in System -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">NIDs in Database</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Models\FakeNid::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Citizen Verification Panel -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Citizen Verification</h3>
                <p class="text-sm text-gray-600">Review and process citizen verification requests</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Pending Requests</p>
                            <p class="text-sm text-gray-600">{{ \App\Models\User::where('verification_status', 'pending')->count() }} citizens waiting for verification</p>
                        </div>
                        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                            Review Now
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Blocked NIDs</p>
                            <p class="text-sm text-gray-600">{{ \App\Models\FakeNid::where('is_blocked', true)->count() }} NIDs are blocked in the system</p>
                        </div>
                        <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700">
                            View Blocked
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Panel -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">System Status</h3>
                <p class="text-sm text-gray-600">Overview of system health and activity</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">NID Database</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Online
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Verification System</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Available NIDs</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ \App\Models\FakeNid::where('is_blocked', false)->where('is_verified', false)->count() }} Ready
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ now()->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
            <p class="text-sm text-gray-600">Latest updates and system activities</p>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul class="-mb-8">
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">System initialized with <span class="font-medium text-gray-900">1000 NIDs</span></p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time>{{ now()->format('M d') }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Authentication system <span class="font-medium text-gray-900">activated</span></p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time>{{ now()->format('M d') }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection