@extends('layouts.citizen')

@section('title', 'Apply for Verification')
@section('page-title', 'Verification Application')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div class="ml-5">
                <h1 class="text-2xl font-bold text-gray-900">Apply for Citizen Verification</h1>
                <p class="text-gray-600">Please provide accurate information to verify your identity</p>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Important Instructions</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>All information must match your National ID Card exactly</li>
                        <li>Upload clear, readable images of your NID (front and back) and passport photo</li>
                        <li>Ensure all uploaded images are in JPEG or PNG format and under 2MB</li>
                        <li>Any false information will result in application rejection</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Form -->
    <form action="{{ route('verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Basic Information Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                <p class="text-sm text-gray-600">Enter your personal details as they appear on your NID</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NID Number -->
                    <div>
                        <label for="nid_number" class="block text-sm font-medium text-gray-700 mb-2">
                            National ID Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nid_number" name="nid_number" value="{{ old('nid_number') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nid_number') border-red-500 @enderror"
                               placeholder="Enter 10-digit NID number" maxlength="10" required>
                        @error('nid_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('full_name') border-red-500 @enderror"
                               placeholder="Enter your full name as per NID" required>
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('date_of_birth') border-red-500 @enderror"
                               required>
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone_number') border-red-500 @enderror"
                               placeholder="01XXXXXXXXX" maxlength="11" required>
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('email') border-red-500 @enderror"
                           placeholder="your.email@example.com" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Family Information Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Family Information</h3>
                <p class="text-sm text-gray-600">Enter family details as they appear on your NID</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Father's Name -->
                    <div>
                        <label for="father_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Father's Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="father_name" name="father_name" value="{{ old('father_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('father_name') border-red-500 @enderror"
                               placeholder="Enter father's full name" required>
                        @error('father_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mother's Name -->
                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Mother's Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="mother_name" name="mother_name" value="{{ old('mother_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('mother_name') border-red-500 @enderror"
                               placeholder="Enter mother's full name" required>
                        @error('mother_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Address Information</h3>
                <p class="text-sm text-gray-600">Provide both permanent and present address details</p>
            </div>
            <div class="p-6 space-y-4">
                <!-- Permanent Address -->
                <div>
                    <label for="permanent_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Permanent Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="permanent_address" name="permanent_address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('permanent_address') border-red-500 @enderror"
                              placeholder="Enter your permanent address as per NID" required>{{ old('permanent_address') }}</textarea>
                    @error('permanent_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Present Address -->
                <div>
                    <label for="present_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Present Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="present_address" name="present_address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('present_address') border-red-500 @enderror"
                              placeholder="Enter your current residential address" required>{{ old('present_address') }}</textarea>
                    @error('present_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Document Upload Card -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Document Upload</h3>
                <p class="text-sm text-gray-600">Upload clear images of required documents</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- NID Front Image -->
                <div>
                    <label for="nid_front_image" class="block text-sm font-medium text-gray-700 mb-2">
                        NID Front Side <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="nid_front_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                    <span>Upload front side of NID</span>
                                    <input id="nid_front_image" name="nid_front_image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" required>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    @error('nid_front_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NID Back Image -->
                <div>
                    <label for="nid_back_image" class="block text-sm font-medium text-gray-700 mb-2">
                        NID Back Side <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="nid_back_image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                    <span>Upload back side of NID</span>
                                    <input id="nid_back_image" name="nid_back_image" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" required>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    @error('nid_back_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Passport Photo -->
                <div>
                    <label for="passport_photo" class="block text-sm font-medium text-gray-700 mb-2">
                        Passport Size Photo <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="passport_photo" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                    <span>Upload passport photo</span>
                                    <input id="passport_photo" name="passport_photo" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" required>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    @error('passport_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('citizen.dashboard') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Submit Application
            </button>
        </div>
    </form>
</div>

<script>
// Add file upload preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['nid_front_image', 'nid_back_image', 'passport_photo'];
    
    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const label = input.parentElement;
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                label.querySelector('span').textContent = file.name;
                label.classList.add('text-green-600');
            }
        });
    });

    // NID number formatting
    document.getElementById('nid_number').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    // Phone number formatting
    document.getElementById('phone_number').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
});
</script>
@endsection