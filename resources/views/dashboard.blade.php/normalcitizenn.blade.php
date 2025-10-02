<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Citizen Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Main Background: Very light gray-blue */
    body {
        background-color: #F5F7FA;
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
        background-color: #EDF2F7; /* Soft gray-blue for hover/active */
        color: #2C5C9C; /* Deep Blue for text on hover */
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
      background-color: #FFFFFF; /* Pure white */
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

    <!-- Sidebar - Subtle blue-gray with 3D vibe -->
    <aside class="w-64 bg-[#E6EFF5] sidebar-container-3d flex flex-col rounded-r-xl overflow-hidden flex-shrink-0">
      <div class="text-center p-6 border-b border-gray-200 font-extrabold text-2xl text-white bg-[#2C5C9C]">
        Citizen Dashboard
      </div>
      <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="#" id="searchPropertyLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-[#333333] hover:text-[#2C5C9C]" data-modal-target="searchPropertyModal">
          <span class="mr-3 text-lg">🔍</span> Search Property
        </a>
        <a href="#" id="myPropertiesLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-[#333333] hover:text-[#2C5C9C]" data-modal-target="myPropertiesModal">
          <span class="mr-3 text-lg">🏘️</span> My Properties
        </a>
        <a href="#" id="makeComplaintsLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-[#333333] hover:text-[#2C5C9C]" data-modal-target="makeComplaintsModal">
          <span class="mr-3 text-lg">📝</span> Make Complaints
        </a>
        <a href="#" id="registerPropertyLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-[#333333] hover:text-[#2C5C9C]" data-modal-target="registerPropertyModal">
          <span class="mr-3 text-lg">🏡</span> Register Property
        </a>
        <a href="#" id="managePropertyLink" class="sidebar-link-hover flex items-center px-4 py-3 rounded-lg text-[#333333] hover:text-[#2C5C9C]" data-modal-target="managePropertyModal">
          <span class="mr-3 text-lg">⚙️</span> Manage Property
        </a>
      </nav>
      <div class="p-4 border-t border-gray-200 flex-shrink-0">
        <button id="logoutButton" class="w-full text-left px-4 py-3 text-red-600 hover:text-red-700 rounded-lg logout-button-3d-hover">
            <span class="mr-2">➡️</span> Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-auto bg-[#F5F7FA]">
      <h1 class="text-3xl font-extrabold text-[#333333] mb-8">Welcome, Citizen!</h1>

      <!-- Dashboard Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <div id="propertiesOwnedCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-modal-target="myPropertiesModal">
          <h2 class="text-xl font-semibold text-[#333333] mb-2">Properties You Own</h2>
          <p class="mt-2 text-4xl font-extrabold text-green-600">2</p>
        </div>

        <div id="pendingComplaintsCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-modal-target="makeComplaintsModal">
          <h2 class="text-xl font-semibold text-[#333333] mb-2">Pending Complaints</h2>
          <p class="mt-2 text-4xl font-extrabold text-[#FF6B35]">1</p>
        </div>

        <div id="propertyRequestsSentCard" class="card-hover-effect bg-white p-6 rounded-xl shadow-lg border border-gray-100" data-modal-target="searchPropertyModal">
          <h2 class="text-xl font-semibold text-[#333333] mb-2">Property Requests Sent</h2>
          <p class="mt-2 text-4xl font-extrabold text-[#4A90E2]">3</p>
        </div>

      </div>

      <!-- Example of adding more sections - Recent Activity -->
      <div class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
        <h2 class="text-2xl font-semibold text-[#333333] mb-4">Your Recent Activity</h2>
        <ul class="space-y-3 text-[#333333]">
            <li class="flex items-center">
                <span class="bg-[#4A90E2] text-white text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">New</span>
                Submitted a property search request for "Downtown Apartments". <span class="text-gray-500 text-sm ml-auto">15 min ago</span>
            </li>
            <li class="flex items-center">
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">Update</span>
                Property ID #123 verification status updated to "Approved". <span class="text-gray-500 text-sm ml-auto">3 hours ago</span>
            </li>
            <li class="flex items-center">
                <span class="bg-[#FF6B35] text-white text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">Complaint</span>
                Complaint #C001 status changed to "In Progress". <span class="text-gray-500 text-sm ml-auto">Yesterday</span>
            </li>
        </ul>
      </div>

      <!-- Footer Section (copied from base code) -->
      <footer class="mt-12 p-6 bg-white rounded-xl shadow-lg border border-gray-100 text-center text-[#333333] text-sm">
        <p>&copy; 2025 Citizen Dashboard. All rights reserved.</p>
        <p class="mt-2">Designed with ❤️ using Tailwind CSS</p>
      </footer>

    </main>
  </div>

  <!-- MODALS (Pop-ups) -->

  <!-- Generic Modal (can be repurposed) -->
  <div id="genericModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 id="modalTitle" class="text-2xl font-semibold text-[#333333]">Modal Title</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('genericModal')">&times;</button>
      </div>
      <div id="modalBody" class="text-[#333333]">
        <p>This is the content for the modal.</p>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-[#4A90E2] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('genericModal')">Close</button>
      </div>
    </div>
  </div>

  <!-- Specific Modals for Sidebar Navigation -->
  <div id="searchPropertyModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-[#333333]">Search Properties</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('searchPropertyModal')">&times;</button>
      </div>
      <div class="text-[#333333]">
        <p>Here you can search for available properties based on various criteria like location, type, and price range.</p>
        <div class="mt-4 p-4 bg-gray-100 rounded-lg">
            <input type="text" placeholder="Enter location or property ID" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4A90E2]">
            <button class="mt-3 bg-[#4A90E2] text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Search</button>
        </div>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-[#4A90E2] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('searchPropertyModal')">Explore Listings</button>
      </div>
    </div>
  </div>

  <div id="myPropertiesModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-[#333333]">My Properties</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('myPropertiesModal')">&times;</button>
      </div>
      <div class="text-[#333333]">
        <p>View details of properties you currently own or are associated with.</p>
        <ul class="list-disc list-inside mt-4 space-y-2">
            <li>Property ID: #123 - 123 Main St, City (Owned)</li>
            <li>Property ID: #456 - 456 Oak Ave, Town (Owned)</li>
        </ul>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-[#4A90E2] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('myPropertiesModal')">Manage My Properties</button>
      </div>
    </div>
  </div>

  <div id="makeComplaintsModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-[#333333]">Make a Complaint</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('makeComplaintsModal')">&times;</button>
      </div>
      <div class="text-[#333333]">
        <p>Submit a new complaint or view the status of your existing complaints.</p>
        <div class="mt-4 p-4 bg-gray-100 rounded-lg">
            <textarea placeholder="Describe your complaint..." class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF6B35]" rows="4"></textarea>
            <button class="mt-3 bg-[#FF6B35] text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition duration-200">Submit Complaint</button>
        </div>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-[#4A90E2] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('makeComplaintsModal')">View My Complaints</button>
      </div>
    </div>
  </div>

  <div id="registerPropertyModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-[#333333]">Register a New Property</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('registerPropertyModal')">&times;</button>
      </div>
      <div class="text-[#333333]">
        <p>Fill out the form to register a new property under your ownership.</p>
        <div class="mt-4 p-4 bg-gray-100 rounded-lg">
            <input type="text" placeholder="Property Address" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4A90E2] mb-2">
            <input type="text" placeholder="Property Type (e.g., House, Apartment)" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4A90E2]">
            <button class="mt-3 bg-[#4A90E2] text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Submit Registration</button>
        </div>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-[#4A90E2] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('registerPropertyModal')">Go to Registration</button>
      </div>
    </div>
  </div>

  <div id="managePropertyModal" class="modal-overlay">
    <div class="modal-content">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold text-[#333333]">Manage Property Details</h3>
        <button class="text-gray-500 hover:text-gray-700 text-3xl font-bold" onclick="closeModal('managePropertyModal')">&times;</button>
      </div>
      <div class="text-[#333333]">
        <p>Update details, view documents, or transfer ownership for your registered properties.</p>
        <ul class="list-disc list-inside mt-4 space-y-2">
            <li>Select Property ID: <select class="ml-2 p-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#4A90E2]"><option>#123</option><option>#456</option></select></li>
        </ul>
      </div>
      <div class="mt-6 flex justify-end">
        <button class="bg-[#4A90E2] text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200" onclick="closeModal('managePropertyModal')">Open Property Manager</button>
      </div>
    </div>
  </div>

  <!-- JavaScript for modal functionality -->
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
          if (modalId === "myPropertiesModal") {
              openModal("myPropertiesModal");
          } else if (modalId === "makeComplaintsModal") {
              openModal("makeComplaintsModal");
          } else if (modalId === "searchPropertyModal") {
              openModal("searchPropertyModal");
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

      // Example of dynamic update (optional) - for Properties You Own
      const propertiesOwnedCountElement = document.querySelector('#propertiesOwnedCard .text-green-600');
      let currentPropertiesOwned = parseInt(propertiesOwnedCountElement.textContent);

      setTimeout(() => {
        currentPropertiesOwned++;
        propertiesOwnedCountElement.textContent = currentPropertiesOwned;
      }, 10000); // Update after 10 seconds
    });
  </script>
</body>
</html>