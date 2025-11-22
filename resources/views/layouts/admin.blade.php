<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Citizen Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body h-full">
    @php
        $adminDisplayName = Auth::user()->display_name;
        $pendingPropertyRequests = \App\Models\PropertyRequest::where('status', 'pending')->count();
        $pendingRentalRequests = \App\Models\RentalRequest::where('status', 'pending')->count();
        $outstandingAssessments = \App\Models\TaxAssessment::outstanding()->count();
        $overdueBadge = app(\App\Services\RevenueDashboardService::class)->getOverdueCount();
    @endphp
    <div class="flex min-h-screen h-full flex-col gap-6 px-4 py-6 md:flex-row md:px-8">
        <!-- Admin Sidebar -->
        <div class="hidden md:flex md:w-72 md:flex-col">
            <div class="app-sidebar-panel flex flex-col flex-grow overflow-y-auto">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold tracking-wide">Admin Panel</h1>
                    <span class="text-xs uppercase tracking-[0.4em] text-[#a8acd4]">CM</span>
                </div>

                <!-- Admin Info -->
                <div class="mt-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-base font-semibold text-white">
                            {{ substr($adminDisplayName, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $adminDisplayName }}</p>
                            <p class="text-xs text-[#aeb1d9]">Administrator</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-8 flex-1 space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="app-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                        </svg>
                        <span class="flex-1">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.verification.index') }}"
                       class="app-nav-link {{ request()->routeIs('admin.verification.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="flex-1">Citizen Verification</span>
                    </a>

                    <div class="pt-2">
                        <p class="px-2 text-[0.65rem] font-semibold uppercase tracking-[0.35em] text-[#9ea3cf]">Property Suite</p>
                    </div>

                    <a href="{{ route('admin.properties.index') }}"
                       class="app-nav-link {{ request()->routeIs('admin.properties.index', 'admin.properties.create', 'admin.properties.edit') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                        </svg>
                        <span class="flex-1">Properties</span>
                    </a>

                    <a href="{{ route('admin.properties.requests') }}"
                       class="app-nav-link {{ request()->routeIs('admin.properties.requests', 'admin.properties.requests.handle') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m-6 4h6m5 5H6a2 2 0 01-2-2V5a2 2 0 012-2h7.586a1 1 0 01.707.293l6.414 6.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="flex-1">Property Requests</span>
                        @if($pendingPropertyRequests > 0)
                            <span class="app-nav-link__badge">{{ $pendingPropertyRequests }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.properties.rentals') }}"
                       class="app-nav-link {{ request()->routeIs('admin.properties.rentals', 'admin.rental-requests.handle') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4m4 0v5m8-5v5" />
                        </svg>
                        <span class="flex-1">Rental Requests</span>
                        @if($pendingRentalRequests > 0)
                            <span class="app-nav-link__badge">{{ $pendingRentalRequests }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.taxes.index') }}"
                       class="app-nav-link {{ request()->routeIs('admin.taxes.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="flex-1">Tax Management</span>
                        @if($outstandingAssessments > 0)
                            <span class="app-nav-link__badge">{{ $outstandingAssessments }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.revenue.index') }}"
                       class="app-nav-link {{ request()->routeIs('admin.revenue.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V3h2v8h-2zm-4 4V7H5v8h2zm8 6V5h-2v16h2zm4-10V9h-2v2h2z" />
                        </svg>
                        <span class="flex-1">Revenue & Compliance</span>
                        @if($overdueBadge > 0)
                            <span class="app-nav-link__badge">{{ $overdueBadge }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.complaints.index') }}"
                       class="app-nav-link {{ request()->routeIs('admin.complaints.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="flex-1">Complaints</span>
                    </a>

                    <a href="{{ route('admin.rent-agreements.index') }}"
                       class="app-nav-link {{ request()->routeIs('admin.rent-agreements.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4 6 6" />
                        </svg>
                        <span class="flex-1">Rent Agreements</span>
                    </a>
                </nav>

                <!-- Logout Button -->
                <div class="mt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="app-nav-link justify-center text-sm font-semibold text-white/90">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-1 flex-col overflow-hidden rounded-[32px] border border-[#d7dbf5] bg-white/30 shadow-[18px_18px_36px_rgba(190,194,214,0.55),_-10px_-10px_28px_rgba(255,255,255,0.85)] backdrop-blur-xl">
            <!-- Top Navigation Bar -->
            <header class="app-topbar m-4">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3 text-[#1f2340]">
                        <button class="md:hidden text-[#4b516c] hover:text-[#1f2340] focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-3 text-sm text-[#4b516c]">
                        <span>Welcome back, {{ $adminDisplayName }}!</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-4 pb-6 pt-2 md:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>