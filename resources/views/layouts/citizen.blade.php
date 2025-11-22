<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Citizen Dashboard') - Citizen Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body h-full">
    @php
        $currentUser = Auth::user();
        $displayName = $currentUser->display_name;
        $isVerified = $currentUser->verification_status === 'verified';
        $hasPendingRequest = $currentUser->verification_status === 'pending' && $currentUser->verification_requested_at;
    @endphp
    <div class="flex min-h-screen h-full flex-col gap-6 px-4 py-6 md:flex-row md:px-8">
        <!-- Citizen Sidebar -->
        <div class="hidden md:flex md:w-72 md:flex-col">
            <div class="app-sidebar-panel flex flex-col flex-grow overflow-y-auto">
                <div class="flex items-center justify-between">
                    <h1 class="text-lg font-semibold tracking-wide">Citizen Portal</h1>
                    <span class="text-xs uppercase tracking-[0.4em] text-[#a8acd4]">CP</span>
                </div>
                
                <!-- Citizen Info -->
                <div class="mt-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-base font-semibold text-white">
                            {{ substr($displayName, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $displayName }}</p>
                            @if($isVerified)
                                <span class="app-chip" data-variant="verified">✓ Verified</span>
                            @elseif($hasPendingRequest)
                                <span class="app-chip" data-variant="pending">Pending review</span>
                            @else
                                <span class="app-chip" data-variant="unverified">Not verified</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="mt-8 flex-1 space-y-2">
                    <a href="{{ route('citizen.dashboard') }}"
                       class="app-nav-link {{ request()->routeIs('citizen.dashboard') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                        </svg>
                        <span class="flex-1">Dashboard</span>
                    </a>

                    <a href="{{ route('citizen.profile') }}"
                       class="app-nav-link {{ request()->routeIs('citizen.profile') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="flex-1">My Profile</span>
                    </a>

                    @if(!$isVerified)
                        <a href="{{ route('verification.create') }}"
                           class="app-nav-link {{ request()->routeIs('verification.*') ? 'is-active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="flex-1">Apply for Verification</span>
                        </a>
                    @endif

                    <a href="{{ route('citizen.properties.index') }}"
                       class="app-nav-link {{ request()->routeIs('citizen.properties.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="flex-1">My Properties</span>
                    </a>

                    <a href="{{ route('citizen.taxes.index') }}"
                       class="app-nav-link {{ request()->routeIs('citizen.taxes.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="flex-1">Tax Payments</span>
                    </a>

                    <a href="{{ route('citizen.complaints.index') }}"
                       class="app-nav-link {{ request()->routeIs('citizen.complaints.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="flex-1">File Complaint</span>
                    </a>

                    <a href="{{ route('citizen.rent-agreements.index') }}"
                       class="app-nav-link {{ request()->routeIs('citizen.rent-agreements.*') ? 'is-active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8m11 8H5a2 2 0 01-2-2V6a2 2 0 012-2h6l4 4h4a2 2 0 012 2v8a2 2 0 01-2 2z" />
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
                        <span>Welcome, {{ $displayName }}!</span>
                        @if($isVerified)
                            <span class="app-chip" data-variant="verified">Verified citizen</span>
                        @endif
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