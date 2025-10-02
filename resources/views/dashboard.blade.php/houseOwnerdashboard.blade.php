<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>House Owner Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Custom body background to match login/admin dashboard's linear gradient */
    body {
        background: linear-gradient(145deg, #d3d8ff, #eef1ff);
    }

    /* Custom styles for a more "3D-ish" feel (card hover effects) */
    .card-hover-effect {
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
      backface-visibility: hidden; /* Helps prevent flickering during transform */
      perspective: 1000px; /* Gives elements a vanishing point for perspective transforms */
    }
    .card-hover-effect:hover {
      transform: translateY(-8px) scale(1.02); /* More pronounced lift and slight scale */
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); /* Even stronger shadow for depth */
      cursor: pointer; /* Indicate it's clickable */
    }
    /* Sidebar container base style for 3D feel */
    .sidebar-container-3d {
        /* Soft neumorphic shadow for the entire sidebar */
        box-shadow: 8px 8px 16px rgba(180, 180, 255, 0.4), -8px -8px 16px rgba(255, 255, 255, 0.7);
    }
    /* Sidebar link hover effect adjusted for light background */
    .sidebar-link-hover {
        transition: transform 0.2s ease-in-out, background-color 0.2s, color 0.2s;
    }
    .sidebar-link-hover:hover {
        transform: translateY(-2px); /* More pronounced lift */
        background-color: #dce0ff; /* A slightly darker shade for hover */
        color: #4f46e5; /* A vibrant indigo for text on hover */
        box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.08), -3px -3px 8px rgba(255, 255, 255, 0.6); /* Subtle 3D shadow */
    }
    /* Logout button 3D hover effect */
    .logout-button-3d-hover {
        transition: transform 0.2s ease-in-out, background-color 0.2s, box-shadow 0.2s;
    }
    .logout-button-3d-hover:hover {
        transform: translateY(-2px); /* Slight lift */
        background-color: #ffebeb; /* Very light red for hover */
        /* Subtle 3D shadow for "pressed" feel */
        box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.05), -3px -3px 8px rgba(255, 255, 255, 0.5);
    }

    /* Modal specific styles (copied from base code) */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6); /* Dark semi-transparent background */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000; /* Ensure it's on top */
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
    }
    .modal-overlay.show {
      opacity: 1;
      visibility: visible;
    }
    .modal-content {
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3); /* Stronger shadow for "pop" */
      max-width: 500px;
      width: 90%;
      transform: translateY(-20px) scale(0.9); /* Initial state for animation */
      opacity: 0;
      transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    .modal-overlay.show .modal-content {
      transform: translateY(0) scale(1); /* Final state for animation */
      opacity: 1;
    }
  </style>
</head>
<body class="min-h-screen">

  <div class="flex flex-grow-1 overflow-hidden"> 

    <aside class="w-64 bg-[#e8ebff] shadow-xl sidebar-container-3d flex flex-col rounded-r-xl overflow-hidden flex-shrink-0">
      <div class="text-center p-6 border-b border-[#dce0ff] font-extrabold text-2xl text-indigo-700 bg-[#eef1ff]">
        Owner Dashboard
      </div>
      <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="properties.html" id="totalPropertiesLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-redirect-url="properties.html">
          <span class="mr-3 text-lg">🏠</span> Total Properties Listed
        </a>
        <a href="#" id="totalTenantsLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="totalTenantsModal">
          <span class="mr-3 text-lg">👥</span> Total Active Tenants
        </a>
        <a href="#" id="pendingRentLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="pendingRentModal">
          <span class="mr-3 text-lg">📩</span> Pending Rent Requests
        </a>
        <a href="#" id="upcomingRentLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="upcomingRentModal">
          <span class="mr-3 text-lg">💸</span> Upcoming Rent Collection
        </a>
      </nav>
      <div class="p-4 border-t border-[#dce0ff] flex-shrink-0">
        <button id="logoutButton" class="w-full text-left px-4 py-3 text-red-600 hover:text-red-700 rounded-lg logout-button-3d-hover">
            <span class="mr-2">➡️</span> Logout
        </button>
      </div>
    </aside>

    <main class="flex-1 p-8 overflow-auto bg-[#e8ebff]">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Welcome, House Owner!</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div id="propertiesListedCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-redirect-url="properties.html">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Total Properties Listed</h2>
          <p class="mt-2 text-4xl font-extrabold text-indigo-600">12</p>
        </div>

        <div id="activeTenantsCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-modal-target="totalTenantsModal">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Total Active Tenants</h2>
          <p class="mt-2 text-4xl font-extrabold text-green-600">8</p>
        </div>

        <div id="pendingRentRequestsCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-modal-target="pendingRentModal">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Pending Rent Requests</h2>
          <p class="mt-2 text-4xl font-extrabold text-yellow-500">3</p>
        </div>

        <div id="upcomingRentCollectionCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-modal-target="upcomingRentModal">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Upcoming Rent Collection</h2>
          <p class="mt-2 text-4xl font-extrabold text-red-600">৳ 25,000</p>
        </div>
      </div>

      <div class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Tenant Activity</h2>
        <ul class="space-y-3 text-gray-600">
            <li class="flex items-center">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">New</span>
                Tenant "Alice Brown" viewed Property ID: 105. <span class="text-gray-400 text-sm ml-auto">10 min ago</span>
            </li>
            <li class="flex items-center">
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">Payment</span>
                Rent payment received from "Bob White" for property 201. <span class="text-gray-400 text-sm ml-auto">2 hours ago</span>
            </li>
            <li class="flex items-center">
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">Request</span>
                Maintenance request received for Property ID: 102. <span class="text-gray-400 text-sm ml-auto">Yesterday</span>
            </li>
        </ul>
      </div>

      <footer class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100 text-center text-gray-600 text-sm">
        <p>&copy; 2025 House Owner Dashboard. All rights reserved.</p>
        <p class="mt-2">Designed with ❤️ using Tailwind CSS</p>
      </footer>
    </main>
  </div>

  <div id="genericModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 id="modalTitle" class="text-2xl font-semibold text-gray-800">Modal Title</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('genericModal')">&times;</button>
      </div>
      <div id="modalBody" class="text-gray-700">
        <p>This is the content for the modal.</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('genericModal')">Close</button>
      </div>
    </div>
  </div>

  <div id="totalTenantsModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Active Tenants</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('totalTenantsModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>This section displays a list of all active tenants, their contact information, and lease details.</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('totalTenantsModal')">View Tenant Directory</button>
      </div>
    </div>
  </div>

  <div id="pendingRentModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Pending Rent Requests</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('pendingRentModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>You can review and approve/deny pending rent requests here.</p>
        <ul class="list-disc list-inside mt-4 space-y-2">
            <li>Request from Tenant X for Property 103</li>
            <li>Request from Tenant Y for Property 105</li>
        </ul>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('pendingRentModal')">Review Requests</button>
      </div>
    </div>
  </div>

  <div id="upcomingRentModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Upcoming Rent Collection</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('upcomingRentModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>View a summary of upcoming rent payments due from your tenants.</p>
        <p class="mt-2 text-xl font-bold text-green-700">Total Due Next 7 Days: ৳ 15,000</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('upcomingRentModal')">View Schedule</button>
      </div>
    </div>
  </div>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- MODAL FUNCTIONS ---
      function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.add('show');
          document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
      }

      window.closeModal = function(modalId) { // Make it global so HTML onclick can access it
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('show');
          document.body.style.overflow = ''; // Restore scrolling
        }
      }

      // Close modal when clicking outside of the content
      document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(event) {
          if (event.target === overlay) { // Check if the click was directly on the overlay
            closeModal(overlay.id);
          }
        });
      });

      // --- EVENT LISTENERS FOR SIDEBAR LINKS ---
      document.querySelectorAll('nav a').forEach(link => { // Select all 'a' tags in nav
        link.addEventListener('click', function(event) {
          const redirectUrl = this.getAttribute('data-redirect-url');
          const modalId = this.getAttribute('data-modal-target');

          if (redirectUrl) {
            event.preventDefault(); // Prevent default link behavior
            window.location.href = redirectUrl; // Redirect to the specified URL
          } else if (modalId) {
            event.preventDefault(); // Prevent default link behavior
            openModal(modalId); // Open the modal
          }
        });
      });

      // --- EVENT LISTENERS FOR DASHBOARD CARDS ---
      document.querySelectorAll('.grid > div').forEach(card => { // Select all divs in grid
        card.addEventListener('click', function() {
          const redirectUrl = this.getAttribute('data-redirect-url');
          const modalId = this.getAttribute('data-modal-target');
          
          if (redirectUrl) {
            window.location.href = redirectUrl; // Redirect to the specified URL
          } else if (modalId) {
            // Use specific modals or generic based on data-modal-target
            if (modalId === "totalTenantsModal") {
                openModal("totalTenantsModal");
            } else if (modalId === "pendingRentModal") {
                openModal("pendingRentModal");
            } else if (modalId === "upcomingRentModal") {
                openModal("upcomingRentModal");
            } else {
                // Fallback to generic modal if no specific one is defined for a new card
                console.log(`No specific modal defined for ${modalId}, using generic modal.`);
                document.getElementById('modalTitle').textContent = 'Details for ' + this.querySelector('h2').textContent;
                document.getElementById('modalBody').innerHTML = '<p>More detailed information about this section would be displayed here.</p>';
                openModal('genericModal');
            }
          }
        });
      });

      // --- LOGOUT BUTTON ---
      const logoutButton = document.getElementById('logoutButton');
      if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
          event.preventDefault();
          const confirmLogout = confirm('Are you sure you want to log out?');
          if (confirmLogout) {
            alert('Logging out...');
            // In a real application, you would redirect to a login page:
            // window.location.href = '/logout';
          }
        });
      }

      // --- Example of dynamic updates (optional) ---
      // This part can remain for testing/demonstration if needed.
      // For a real app, these values would come from a backend.
      // const propertiesCountElement = document.querySelector('#propertiesListedCard .text-indigo-600');
      // let currentProperties = parseInt(propertiesCountElement.textContent);
      // setTimeout(() => {
      //   currentProperties++;
      //   propertiesCountElement.textContent = currentProperties;
      // }, 10000); // Update after 10 seconds
    });
  </script>
</body>
</html>