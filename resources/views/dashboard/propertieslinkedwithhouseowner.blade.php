<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Properties List - Owner Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Consistent body background matching your Owner Dashboard */
        body {
            background: linear-gradient(145deg, #d3d8ff, #eef1ff);
        }

        /* Re-using card hover effect for property cards */
        .card-hover-effect {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            backface-visibility: hidden;
            perspective: 1000px;
        }
        .card-hover-effect:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        /* Add a subtle neumorphic shadow for main containers if desired */
        .container-neumorphic-shadow {
            box-shadow: 8px 8px 16px rgba(180, 180, 255, 0.4), -8px -8px 16px rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="w-full p-6 text-center text-indigo-700 bg-[#eef1ff] shadow-lg container-neumorphic-shadow">
        <h1 class="text-3xl font-extrabold">Your Properties</h1>
        <p class="mt-2 text-lg text-gray-700">Manage all your listed properties here.</p>
        <a href="houseOwnerdesh.html" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-md">
            &larr; Back to Dashboard
        </a>
    </header>

    <main class="flex-1 p-8 overflow-auto">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Overview</h2>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 mb-8 flex flex-wrap gap-4 items-center">
                <input type="text" placeholder="Search by address or ID..." class="flex-1 p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400 min-w-[200px]">
                <select class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All Types</option>
                    <option value="house">House</option>
                    <option value="apartment">Apartment</option>
                    <option value="commercial">Commercial</option>
                </select>
                <select class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All Statuses</option>
                    <option value="occupied">Occupied</option>
                    <option value="vacant">Vacant</option>
                    <option value="under_maint">Under Maintenance</option>
                </select>
                <button class="bg-indigo-600 text-white px-5 py-3 rounded-md hover:bg-indigo-700 transition duration-200 shadow-md">Apply Filters</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-xl font-bold text-indigo-700 mb-2">Property ID: 101</h3>
                    <p class="text-gray-700"><strong>Address:</strong> 123 Maple Drive, Springfield</p>
                    <p class="text-gray-700"><strong>Type:</strong> House</p>
                    <p class="mt-2"><strong>Status:</strong> <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-full">Occupied</span></p>
                    <p class="mt-1"><strong>Current Tenant:</strong> Alice Brown</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">৳ 15,000/month</span>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">View Details</button>
                    </div>
                </div>

                <div class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-xl font-bold text-indigo-700 mb-2">Property ID: 102</h3>
                    <p class="text-gray-700"><strong>Address:</strong> Apt 4B, 789 Oak Avenue, Metropolis</p>
                    <p class="text-gray-700"><strong>Type:</strong> Apartment</p>
                    <p class="mt-2"><strong>Status:</strong> <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-full">Occupied</span></p>
                    <p class="mt-1"><strong>Current Tenant:</strong> Bob White</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">৳ 10,000/month</span>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">View Details</button>
                    </div>
                </div>

                <div class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-xl font-bold text-indigo-700 mb-2">Property ID: 103</h3>
                    <p class="text-gray-700"><strong>Address:</strong> 456 Pine Street, Smallville</p>
                    <p class="text-gray-700"><strong>Type:</strong> House</p>
                    <p class="mt-2"><strong>Status:</strong> <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded-full">Vacant</span></p>
                    <p class="mt-1"><strong>Current Tenant:</strong> None</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">৳ 12,000/month</span>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">List for Rent</button>
                    </div>
                </div>

                <div class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-xl font-bold text-indigo-700 mb-2">Property ID: 104</h3>
                    <p class="text-gray-700"><strong>Address:</strong> Unit 2, 101 Commercial Rd, Gotham</p>
                    <p class="text-gray-700"><strong>Type:</strong> Commercial</p>
                    <p class="mt-2"><strong>Status:</strong> <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded-full">Occupied</span></p>
                    <p class="mt-1"><strong>Current Tenant:</strong> Wayne Enterprises</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">৳ 50,000/month</span>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">View Details</button>
                    </div>
                </div>

                <div class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h3 class="text-xl font-bold text-indigo-700 mb-2">Property ID: 105</h3>
                    <p class="text-gray-700"><strong>Address:</strong> 321 Elm Street, Star City</p>
                    <p class="text-gray-700"><strong>Type:</strong> House</p>
                    <p class="mt-2"><strong>Status:</strong> <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-full">Under Maintenance</span></p>
                    <p class="mt-1"><strong>Current Tenant:</strong> None</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-indigo-600">N/A</span>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">Update Status</button>
                    </div>
                </div>

            </div> </div>
    </main>

    <footer class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100 text-center text-gray-600 text-sm max-w-7xl mx-auto w-full">
        <p>&copy; 2025 House Owner Dashboard. All rights reserved.</p>
        <p class="mt-2">Designed with ❤️ using Tailwind CSS</p>
    </footer>

</body>
</html>