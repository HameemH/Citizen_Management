<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Citizen Management</title>
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
            <p class="text-xs text-gray-500">Create your account</p>
        </div>

        <!-- Signup Form -->
        <form method="POST" action="{{ route('register') }}">
        

            <!-- Full Name -->
            <div class="flex items-center mb-4 px-4 py-3 rounded-full bg-[#e8ebff] shadow-[inset_5px_5px_10px_#c2c5d6,_inset_-5px_-5px_10px_#ffffff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="text" name="name" placeholder="Full name"
                       class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400 text-sm" required />
            </div>

            <!-- Email -->
            <div class="flex items-center mb-4 px-4 py-3 rounded-full bg-[#e8ebff] shadow-[inset_5px_5px_10px_#c2c5d6,_inset_-5px_-5px_10px_#ffffff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M2 8l10 6 10-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="email" name="email" placeholder="Email"
                       class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400 text-sm" required />
            </div>

            <!-- Password -->
            <div class="flex items-center mb-4 px-4 py-3 rounded-full bg-[#e8ebff] shadow-[inset_5px_5px_10px_#c2c5d6,_inset_-5px_-5px_10px_#ffffff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M2 8l10 6 10-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="password" name="password" placeholder="Password"
                       class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400 text-sm" required />
            </div>

            <!-- Confirm Password -->
            <div class="flex items-center mb-6 px-4 py-3 rounded-full bg-[#e8ebff] shadow-[inset_5px_5px_10px_#c2c5d6,_inset_-5px_-5px_10px_#ffffff]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M2 8l10 6 10-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <input type="password" name="password_confirmation" placeholder="Confirm Password"
                       class="bg-transparent w-full outline-none text-gray-700 placeholder-gray-400 text-sm" required />
            </div>
            <!-- Signup Button -->
            <button type="submit"
                    class="w-full py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-gray-800 font-semibold hover:scale-105 transition">
                Sign Up
            </button>
        </form>

        <!-- Link to Signin -->
        <p class="text-[11px] text-gray-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 underline">Login</a>
        </p>
    </div>

</body>
</html>