<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Main Background: Linear gradient for a soft, modern look */
    body {
        backgrou      <!-- Logout button functionality -->
      const logoutButton = document.getElementById('logoutButton');
      if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
          event.preventDefault(); // Prevent default button behavior
          const confirmLogout = confirm('Are you sure you want to log out?');
          if (confirmLogout) {
            // Create and submit logout form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
          }
        });
      }adient(145deg, #d3d8ff, #eef1ff);
    }

    /* Custom styles for a more "3D-ish" feel (card hover effects are fine as is) */
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

    /* --- Sidebar specific 3D/Neumorphic adjustments --- */

    /* Sidebar container base style for 3D feel */
    .sidebar-container-3d {
        /* Soft neumorphic shadow for the entire sidebar */
        box-shadow: 8px 8px 16px rgba(180, 180, 255, 0.4), -8px -8px 16px rgba(255, 255, 255, 0.7);
    }

    /* Sidebar link hover effect for 3D vibe */
    .sidebar-link-hover {
        transition: transform 0.2s ease-in-out, background-color 0.2s, box-shadow 0.2s, color 0.2s;
    }
    .sidebar-link-hover:hover {
        transform: translateY(-2px); /* Slight lift */
        background-color: #dce0ff; /* A slightly darker shade for hover */
        color: #4f46e5; /* A vibrant indigo for text on hover */
        /* Subtle inner/outer shadow for 3D "pressed" feel */
        box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.08), -3px -3px 8px rgba(255, 255, 255, 0.6);
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

    /* Modal specific styles */
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

    <aside class="w-64 bg-[#e8ebff] sidebar-container-3d flex flex-col rounded-r-xl overflow-hidden flex-shrink-0">
      <div class="text-center p-6 border-b border-[#dce0ff] font-extrabold text-2xl text-indigo-700 bg-[#eef1ff]">
        Admin Panel
      </div>
      <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="#" id="verifyRequestLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="verifyRequestModal">
          <span class="mr-3 text-lg">✔️</span> Verify Request
        </a>
        <a href="#" id="verifyPropertyLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="verifyPropertyModal">
          <span class="mr-3 text-lg">🏘️</span> Verify Property
        </a>
        <a href="#" id="taxCollectionLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="taxCollectionModal">
          <span class="mr-3 text-lg">💰</span> Tax Collection
        </a>
        <a href="#" id="manageCitizenLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="manageCitizenModal">
          <span class="mr-3 text-lg">👥</span> Manage Citizen
        </a>
        <a href="#" id="manageComplaintsLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-gray-700 hover:text-indigo-600" data-modal-target="manageComplaintsModal">
          <span class="mr-3 text-lg">📩</span> Manage Complaints
        </a>
      </nav>
      <div class="p-4 border-t border-[#dce0ff] flex-shrink-0">
        <button id="logoutButton" class="w-full text-left px-4 py-3 text-red-600 hover:text-red-700 rounded-lg logout-button-3d-hover">
            <span class="mr-2">➡️</span> Logout
        </button>
      </div>
    </aside>

    <main class="flex-1 p-8 overflow-auto bg-[#eef1ff]">
      <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Welcome, {{ auth()->user()->name ?? 'Admin' }}!</h1>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div id="pendingVerificationsCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100 transform hover:-translate-y-1" data-modal-target="pendingVerificationsModal">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Pending Verifications</h2>
          <p class="mt-2 text-4xl font-extrabold text-blue-600">12</p>
        </div>

        <div id="collectedTaxCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100 transform hover:-translate-y-1" data-modal-target="collectedTaxModal">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Collected Tax (This Month)</h2>
          <p class="mt-2 text-4xl font-extrabold text-green-600">৳ 50,000</p>
        </div>

        <div id="openComplaintsCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100 transform hover:-translate-y-1" data-modal-target="openComplaintsModal">
          <h2 class="text-xl font-semibold text-gray-700 mb-2">Open Complaints</h2>
          <p class="mt-2 text-4xl font-extrabold text-red-600">5</p>
        </div>

        <div id="registeredCitizensCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100 transform hover:-translate-y-1" data-modal-target="registeredCitizensModal">
            <h2 class="text-xl font-semibold text-gray-700 mb-2">Total Registered Citizens</h2>
            <p class="mt-2 text-4xl font-extrabold text-purple-600">1,234</p>
        </div>
      </div>

      <div class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Activity Log</h2>
        <ul class="space-y-3 text-gray-600">
            <li class="flex items-center">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">New</span>
                User "John Doe" submitted a property verification request. <span class="text-gray-400 text-sm ml-auto">5 min ago</span>
            </li>
            <li class="flex items-center">
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">Update</span>
                Tax collection updated for "Jane Smith". <span class="text-gray-400 text-sm ml-auto">1 hour ago</span>
            </li>
            <li class="flex items-center">
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">Complaint</span>
                Complaint #007 marked as urgent. <span class="text-gray-400 text-sm ml-auto">3 hours ago</span>
            </li>
        </ul>
      </div>

      <footer class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100 text-center text-gray-600 text-sm">
        <p>&copy; 2025 Admin Dashboard. All rights reserved.</p>
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

  <div id="verifyRequestModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Verify Request Details</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('verifyRequestModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>Here you would see a list of pending verification requests, with options to review and approve/reject them.</p>
        <ul class="list-disc list-inside mt-4 space-y-2">
            <li>Request ID: #VR001 - Property verification for John Doe</li>
            <li>Request ID: #VR002 - Identity verification for Jane Smith</li>
        </ul>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('verifyRequestModal')">View All Requests</button>
      </div>
    </div>
  </div>

  <div id="verifyPropertyModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Verify Property Details</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('verifyPropertyModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>This section would display details about properties submitted for verification, including documents and status.</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('verifyPropertyModal')">Manage Properties</button>
      </div>
    </div>
  </div>

  <div id="taxCollectionModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Tax Collection Overview</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('taxCollectionModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>Detailed information on tax collection, payment history, and overdue accounts.</p>
        <p class="mt-2 text-xl font-bold text-green-700">Total Collected This Quarter: ৳ 150,000</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('taxCollectionModal')">View Tax Reports</button>
      </div>
    </div>
  </div>

  <div id="manageCitizenModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Manage Citizen Records</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('manageCitizenModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>Access and manage citizen profiles, including personal details, registered properties, and historical data.</p>
        <p class="mt-2 text-xl font-bold text-purple-700">New Citizens Registered Today: 3</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('manageCitizenModal')">Search Citizens</button>
      </div>
    </div>
  </div>

  <div id="manageComplaintsModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-gray-800">Manage Citizen Complaints</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('manageComplaintsModal')">&times;</button>
      </div>
      <div class="text-gray-700">
        <p>Review, prioritize, and resolve citizen complaints. Assign them to relevant departments or personnel.</p>
        <p class="mt-2 text-xl font-bold text-red-700">Complaints Requiring Action: 5</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('manageComplaintsModal')">View All Complaints</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.add('show');
          document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }
      }

      window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('show');
          document.body.style.overflow = ''; // Restore scrolling
        }
      }

      // Close modal when clicking outside of it
      document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(event) {
          if (event.target === overlay) { // Only close if clicking the overlay, not its content
            closeModal(overlay.id);
          }
        });
      });

      // Event listeners for all sidebar navigation links
      document.querySelectorAll('nav a[data-modal-target]').forEach(link => {
        link.addEventListener('click', function(event) {
          event.preventDefault(); // Prevent default link behavior (e.g., navigating to #)
          const modalId = this.getAttribute('data-modal-target');
          openModal(modalId);
        });
      });

      // Event listeners for dashboard cards (linking to specific modals)
      document.querySelectorAll('.grid > div[data-modal-target]').forEach(card => {
        card.addEventListener('click', function() {
          const modalId = this.getAttribute('data-modal-target');
          // Map card data-modal-target to the correct specific modal
          if (modalId === "pendingVerificationsModal") {
              openModal("verifyRequestModal");
          } else if (modalId === "collectedTaxModal") {
              openModal("taxCollectionModal");
          } else if (modalId === "openComplaintsModal") {
              openModal("manageComplaintsModal");
          } else if (modalId === "registeredCitizensModal") {
              openModal("manageCitizenModal");
          } else {
              // Fallback for any other card that might be added without a specific modal
              console.log(`No specific modal defined for ${modalId}, using generic modal.`);
              document.getElementById('modalTitle').textContent = 'Details for ' + this.querySelector('h2').textContent;
              document.getElementById('modalBody').innerHTML = '<p>More detailed information about this section would be displayed here.</p>';
              openModal('genericModal');
          }
        });
      });

      // Logout button functionality
      const logoutButton = document.getElementById('logoutButton');
      if (logoutButton) {
        logoutButton.addEventListener('click', function(event) {
          event.preventDefault(); // Prevent default button behavior
          const confirmLogout = confirm('Are you sure you want to log out?');
          if (confirmLogout) {
            alert('Logging out...');
            // In a real application, you would typically redirect to a login page here:
            // window.location.href = '/login';
          }
        });
      }

      // Example of dynamic update (optional)
      const openComplaintsCountElement = document.querySelector('#openComplaintsCard .text-red-600');
      let currentComplaints = parseInt(openComplaintsCountElement.textContent);

      // Simulate a new complaint after 7 seconds
      setTimeout(() => {
        currentComplaints++;
        openComplaintsCountElement.textContent = currentComplaints;
      }, 7000);
    });
  </script>
</body>
</html>