<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Citizen Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(145deg, #d3d8ff, #eef1ff);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans">

  <div class="w-full max-w-3xl mx-auto p-6">

    <!-- Profile Card -->
    <div class="bg-[#e8ebff] rounded-[30px] shadow-[10px_10px_20px_#c2c5d6,_-10px_-10px_20px_#ffffff] p-8">
      
      <!-- Header -->
      <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
        <img src="https://picsum.photos/200" alt="Citizen Photo" class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-indigo-500 shadow-[6px_6px_12px_#c2c5d6,_-6px_-6px_12px_#ffffff]">
        
        <div class="text-center md:text-left">
          <h1 class="text-2xl font-extrabold text-gray-800">Jane Doe</h1>
          <p class="text-sm text-gray-600 mt-1">Citizen ID: <span class="font-semibold text-indigo-600">C1234567</span></p>
          
          <!-- Contact -->
          <div class="mt-4 text-gray-700 space-y-2 text-sm">
            <p class="flex items-center justify-center md:justify-start">
              📞 (555) 123-4567
            </p>
            <p class="flex items-center justify-center md:justify-start">
              ✉️ jane.doe@example.com
            </p>
          </div>
        </div>
      </div>

      <!-- Navbar Buttons -->
      <div class="mt-8 flex gap-3 justify-center flex-wrap">
        <button data-tab="profile" class="tab-btn px-4 py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-indigo-600 font-semibold">Profile</button>
        <button data-tab="property" class="tab-btn px-4 py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-gray-800 font-semibold">Property</button>
        <button data-tab="tax" class="tab-btn px-4 py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-gray-800 font-semibold">Tax Info</button>
        <button data-tab="others" class="tab-btn px-4 py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-gray-800 font-semibold">Others</button>
      </div>

      <!-- Details Sections -->
      <div class="mt-6">
        <!-- Profile Details -->
        <div id="profile" class="tab-content">
          <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Profile Details</h2>
          <p><strong class="text-indigo-600">DOB:</strong> January 1, 1985</p>
          <p><strong class="text-indigo-600">Address:</strong> 123 Main Street, Dhaka</p>
          <p><strong class="text-indigo-600">Gender:</strong> Female</p>
          <p><strong class="text-indigo-600">Occupation:</strong> Software Engineer</p>
        </div>

        <!-- Property Details -->
        <div id="property" class="tab-content hidden">
          <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Property Details</h2>
          <p><strong class="text-indigo-600">Property ID:</strong> P-98765</p>
          <p><strong class="text-indigo-600">Type:</strong> Residential</p>
          <p><strong class="text-indigo-600">Size:</strong> 2000 sqft</p>
          <p><strong class="text-indigo-600">Location:</strong> Gulshan, Dhaka</p>
        </div>

        <!-- Tax Information -->
        <div id="tax" class="tab-content hidden">
          <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Tax Information</h2>
          <p><strong class="text-indigo-600">Tax ID:</strong> TX-445566</p>
          <p><strong class="text-indigo-600">Last Paid:</strong> March 2025</p>
          <p><strong class="text-indigo-600">Pending Dues:</strong> None</p>
          <p><strong class="text-indigo-600">Annual Tax:</strong> $500</p>
        </div>

        <!-- Other Requirements -->
        <div id="others" class="tab-content hidden">
          <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Other Requirements</h2>
          <p><strong class="text-indigo-600">Voter ID:</strong> V123456789</p>
          <p><strong class="text-indigo-600">National ID:</strong> NID-998877</p>
          <p><strong class="text-indigo-600">Status:</strong> Active</p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-6 flex gap-4 justify-center">
        <button class="flex-1 py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-gray-800 font-semibold hover:scale-105 transition">
          Edit
        </button>
        <button class="flex-1 py-2 rounded-full bg-[#e8ebff] shadow-[4px_4px_8px_#c2c5d6,_-4px_-4px_8px_#ffffff] text-red-600 font-semibold hover:scale-105 transition">
          Logout
        </button>
      </div>
    </div>

    <!-- Footer -->
    <p class="text-[11px] text-gray-500 mt-6 text-center">© 2025 Citizen Management Software</p>

  </div>

  <!-- Tab Switching Script -->
  <script>
    const tabBtns = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabBtns.forEach(btn => {
      btn.addEventListener("click", () => {
        // Reset all
        tabBtns.forEach(b => b.classList.remove("text-indigo-600"));
        contents.forEach(c => c.classList.add("hidden"));
        // Activate clicked
        btn.classList.add("text-indigo-600");
        document.getElementById(btn.dataset.tab).classList.remove("hidden");
      });
    });
  </script>
</body>
</html>
