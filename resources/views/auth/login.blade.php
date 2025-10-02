<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Citizen Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(145deg, #d3d8ff, #eef1ff);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="bg-[#e8ebff] p-8 pt-6 rounded-[30px] shadow-[8px_8px_16px_#c2c5d6,_-8px_-8px_16px_#ffffff] w-[360px] text-center">

        <!-- Logo & Title -->
        <div class="mb-6 flex flex-col items-center">
            <!-- Logo Placeholder -->
            <div class="w-14 h-14 rounded-full bg-[#e8ebff] shadow-[inset_4px_4px_8px_#c2c5d6,_inset_-4px_-4px_8px_#ffffff] flex items-center justify-center text-2xl font-bold text-indigo-600">
                🏛️
            </div>
            <!-- App Name -->
            <h1 class="mt-3 text-xl font-extrabold text-gray-800">Citizen Management</h1>
            <p class="text-xs text-gray-500">Login to access your dashboard</p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Field -->
            <div class="flex items-center mb-4 px-4 py-3 rounded-full bg-[#e8ebff] shadow-[inset_5px_5px_10px_#c2c5d6,_inset_-5px_-5px_10px_#ffffff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke-width="2"/>
                    <polyline points="22,6 12,13 2,6" stroke-width="2"/>
                </svg>
                <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                       class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400 text-sm" required />
            </div>

            <!-- Password Field -->
            <div class="flex items-center mb-4 px-4 py-3 rounded-full bg-[#e8ebff] shadow-[inset_5px_5px_10px_#c2c5d6,_inset_-5px_-5px_10px_#ffffff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke-width="2"/>
                    <circle cx="12" cy="16" r="1" stroke-width="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke-width="2"/>
                </svg>
                <input type="password" name="password" placeholder="Enter your password"
                       class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400 text-sm" required />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-xs text-gray-600">
                    <input type="checkbox" name="remember" class="mr-2 rounded">
                    Remember me
                </label>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-xs">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Login Button -->
            <button type="submit"
                    class="w-full py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-gray-800 font-semibold hover:scale-105 transition">
                Login
            </button>
        </form>

        <!-- Link to Signup -->
        <p class="text-[11px] text-gray-500 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 underline">Sign Up</a>
        </p>
        
        <!-- Footer -->
        <p class="text-[11px] text-gray-500 mt-4">© 2025 Citizen Management Software</p>
    </div>

</body>
</html>
