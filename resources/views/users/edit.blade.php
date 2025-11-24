@extends('layouts.main')

@section('title', 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Profile</h1>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6" x-data="profileData()">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h2>

            <form method="POST" action="{{ route('profile.update') }}" @submit.prevent="handleFormSubmit" x-ref="profileForm">
                @csrf

                {{-- Profile Image --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                    <div class="flex items-center gap-6">
                        {{-- Current/Preview Image --}}
                        <div class="relative">
                            <img
                                :src="formData.profileImage.preview || '{{ auth()->user()->profile_image_url ?? 'https://avatar.iran.liara.run/username?username='.urlencode(auth()->user()->first_name.' '.auth()->user()->last_name).'&size=128' }}'"
                                alt="Profile"
                                class="w-24 h-24 rounded-full object-cover border-2 border-gray-200"
                            >
                            <template x-if="formData.profileImage.preview">
                                <button
                                    type="button"
                                    @click="removeProfileImage()"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </template>
                        </div>

                        {{-- Upload Button --}}
                        <div>
                            <input
                                type="file"
                                id="profile-image-input"
                                accept="image/*"
                                @change="handleProfileImageChange($event)"
                                class="hidden"
                            >
                            <label
                                for="profile-image-input"
                                class="cursor-pointer inline-block px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors text-sm"
                            >
                                Upload New Photo
                            </label>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (max 5MB)</p>
                        </div>
                    </div>
                </div>

                {{-- Name Fields --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <x-ui.field required>
                        <x-ui.label>First Name</x-ui.label>
                        <x-ui.input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', auth()->user()->first_name) }}"
                            placeholder="Enter your first name"
                        />
                        <x-ui.error name="first_name" />
                    </x-ui.field>

                    <x-ui.field required>
                        <x-ui.label>Last Name</x-ui.label>
                        <x-ui.input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', auth()->user()->last_name) }}"
                            placeholder="Enter your last name"
                        />
                        <x-ui.error name="last_name" />
                    </x-ui.field>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <x-ui.field required>
                        <x-ui.label>Email</x-ui.label>
                        <x-ui.input
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            placeholder="Enter your email"
                        />
                        <x-ui.error name="email" />
                    </x-ui.field>
                </div>

                {{-- Phone Number --}}
                <div class="mb-4">
                    <x-ui.field>
                        <x-ui.label>Phone Number</x-ui.label>
                        <x-ui.input
                            type="tel"
                            name="phone_number"
                            value="{{ old('phone_number', auth()->user()->phone_number) }}"
                            placeholder="Enter your phone number"
                        />
                        <x-ui.error name="phone_number" />
                    </x-ui.field>
                </div>

                {{-- Citizenship --}}
                <div class="mb-6" x-data="{ citizenship: '{{ old('citizenship', auth()->user()->citizenship) }}' }">
                    <x-ui.field required>
                        <x-ui.label>Citizenship</x-ui.label>
                        <x-ui.select
                            placeholder="Select citizenship"
                            x-model="citizenship"
                            name="citizenship"
                        >
                            <x-ui.select.option value="Kenyan Citizen" label="Kenyan Citizen" />
                            <x-ui.select.option value="Kenyan Resident" label="Kenyan Resident" />
                            <x-ui.select.option value="East Africa Resident" label="East Africa Resident" />
                            <x-ui.select.option value="Non Resident" label="Non Resident" />
                        </x-ui.select>
                        <x-ui.error name="citizenship" />
                    </x-ui.field>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-4">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors"
                        :disabled="isUploading"
                    >
                        <span x-show="!isUploading">Save Changes</span>
                        <span x-show="isUploading">Saving...</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>

            {{-- Loading Overlay --}}
            <div x-show="isUploading" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                <div class="bg-white rounded-xl shadow-2xl p-8 text-center">
                    <div class="inline-block animate-spin rounded-full h-16 w-16 border-b-4 border-orange-600 mb-4"></div>
                    <p class="text-lg font-semibold text-gray-700">Uploading profile image...</p>
                </div>
            </div>
        </div>

        {{-- Email Verification Section --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Email Verification</h2>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if(auth()->user()->hasVerifiedEmail())
                        <div class="flex items-center gap-2 px-4 py-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-semibold">Email Verified</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 px-4 py-2 bg-yellow-100 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-yellow-800 font-semibold">Email Not Verified</span>
                        </div>
                    @endif
                </div>

                @if(!auth()->user()->hasVerifiedEmail())
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors text-sm"
                    >
                        Resend Verification Email
                    </button>
                </form>
                @endif
            </div>

            <p class="text-sm text-gray-600 mt-4">
                @if(auth()->user()->hasVerifiedEmail())
                    Your email is verified. You can create trips and access all features.
                @else
                    Please verify your email by clicking the link sent to <strong>{{ auth()->user()->email }}</strong>.
                    If using the log driver, check <code class="bg-gray-100 px-1 rounded">storage/logs/laravel.log</code> for the verification link.
                @endif
            </p>
        </div>
    </div>
</div>

<script>
function profileData() {
    return {
        isUploading: false,
        formData: {
            profileImage: { file: null, preview: null }
        },

        handleProfileImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'error', content: 'Image must be less than 5MB' }
                }));
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'error', content: 'Please select an image file' }
                }));
                return;
            }

            this.formData.profileImage.file = file;
            this.formData.profileImage.preview = URL.createObjectURL(file);
        },

        removeProfileImage() {
            this.formData.profileImage = { file: null, preview: null };
            document.getElementById('profile-image-input').value = '';
        },

        async handleFormSubmit(event) {
            event.preventDefault();

            try {
                // Upload profile image if selected
                if (this.formData.profileImage.file) {
                    this.isUploading = true;
                    const imageUrl = await this.uploadToCloudinary(this.formData.profileImage.file);
                    this.addHiddenInput('profile_image_url', imageUrl);
                }

                // Submit the form
                this.$refs.profileForm.submit();
            } catch (error) {
                this.isUploading = false;
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'error', content: error.message || 'Failed to upload image' }
                }));
            }
        },

        addHiddenInput(name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            this.$refs.profileForm.appendChild(input);
        },

        async uploadToCloudinary(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('upload_preset', '{{ config("cloudinary.upload_preset") }}');
            formData.append('folder', 'triply/profiles');

            const response = await fetch('https://api.cloudinary.com/v1_1/{{ config("cloudinary.cloud_name") }}/image/upload', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error?.message || 'Upload failed');
            }

            const data = await response.json();
            return data.secure_url;
        }
    }
}
</script>
@endsection
