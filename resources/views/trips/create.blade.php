@extends('layouts.main')

@section('title', 'Create Travel Package')

@section('content')
<div class="min-h-screen flex items-center justify-center py-0 sm:py-12 px-0 sm:px-2 md:px-8 bg-gray-50">
    <main class="w-full max-w-4xl" x-data="tripFormData()">
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-6 bg-linear-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">Create a New Travel Package</h1>

            <form method="POST" action="/create-travel-package" @submit.prevent="handleFormSubmit" class="space-y-6" autocomplete="off" x-ref="tripForm">
                @csrf

                {{-- SECTION 1: Basic Information --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>

                    <x-ui.field required>
                        <x-ui.label>Trip Title</x-ui.label>
                        <x-ui.input name="title" type="text" placeholder="e.g., 5 Days Zanzibar Beach Paradise" class="h-12" value="{{ old('title') }}" @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()" />
                        <x-ui.error name="title" />
                    </x-ui.field>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.field required>
                            <x-ui.label>Destination</x-ui.label>
                            <x-ui.input name="destination" type="text" placeholder="e.g., Zanzibar" class="h-12" value="{{ old('destination') }}" @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()" />
                            <x-ui.error name="destination" />
                        </x-ui.field>

                        <x-ui.field required>
                            <x-ui.label>Hotel Name</x-ui.label>
                            <x-ui.input name="hotel_name" type="text" placeholder="e.g., Serena Beach Resort" class="h-12" value="{{ old('hotel_name') }}" @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()" />
                            <x-ui.error name="hotel_name" />
                        </x-ui.field>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.field required>
                            <x-ui.label>Trip Type</x-ui.label>
                            <x-ui.select placeholder="Select trip type" x-model="type" name="type" triggerClass="!h-12 !py-0 !flex !items-center" @change="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()">
                                <x-ui.select.option value="Beach" label="Beach" />
                                <x-ui.select.option value="Safari" label="Safari" />
                                <x-ui.select.option value="Adventure" label="Adventure" />
                                <x-ui.select.option value="Cultural" label="Cultural" />
                                <x-ui.select.option value="Luxury" label="Luxury" />
                                <x-ui.select.option value="Budget" label="Budget" />
                                <x-ui.select.option value="Other" label="Other" />
                            </x-ui.select>
                            <x-ui.error name="type" />
                        </x-ui.field>

                        <x-ui.field required>
                            <x-ui.label>Duration (Days)</x-ui.label>
                            <x-ui.input name="duration_days" type="number" placeholder="e.g., 5" min="1" class="h-12" value="{{ old('duration_days') }}" @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()" />
                            <x-ui.error name="duration_days" />
                        </x-ui.field>
                    </div>

                  <x-ui.field required>
                    <x-ui.label>Description</x-ui.label>
                    <textarea
                        name="description"
                        placeholder="Describe the trip experience..."
                        rows="4"
                        class="inline-block p-2 w-full text-base sm:text-sm text-neutral-800 placeholder-neutral-400 bg-white shadow-sm border rounded-lg border-black/10 focus:border-black/15 focus:ring-2 focus:ring-neutral-900/15 focus:ring-offset-0 focus:outline-none transition-colors duration-200 resize-none"
                        @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()"
                    >{{ old('description') }}</textarea>
                    <x-ui.error name="description" />
                </x-ui.field>

                </div>

                <hr class="my-8 border-gray-200">

                {{-- SECTION 2: Pricing --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Pricing</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-ui.field required>
                            <x-ui.label>Base Price Per Person (Ksh)</x-ui.label>
                            <x-ui.input name="base_price_per_person" type="number" placeholder="e.g., 45000" min="0" step="0.01" class="h-12" value="{{ old('base_price_per_person') }}" @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()" />
                            <x-ui.error name="base_price_per_person" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Child Discount (%)</x-ui.label>
                            <x-ui.input name="child_discount_percent" type="number" placeholder="e.g., 20" min="0" max="100" step="0.01" class="h-12" value="{{ old('child_discount_percent', '0') }}" @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()" />
                            <x-ui.error name="child_discount_percent" />
                        </x-ui.field>
                    </div>

                    {{-- Transport Options --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transport Options</label>
                        <template x-for="(transport, index) in formData.transport_options" :key="index">
                            <div class="flex flex-col md:flex-row gap-2 mb-3">
                                <input type="text" x-model="transport.name" placeholder="Name (e.g., Plane)" class="flex-1 min-h-12 h-12 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <input type="text" x-model="transport.from" placeholder="From (optional)" class="flex-1 min-h-12 h-12 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <input type="number" x-model="transport.price" placeholder="Price (Ksh)" class="w-full md:w-32 min-h-12 h-12 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <button type="button" @click="formData.transport_options.splice(index, 1)" class="px-4 min-h-12 h-12 text-red-600 hover:bg-red-50 rounded-lg font-medium whitespace-nowrap">Remove</button>
                            </div>
                        </template>
                        <button type="button" @click="formData.transport_options.push({ name: '', from: '', price: '' })" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">+ Add Transport Option</button>
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                {{-- SECTION 3: Images --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Images</h2>

                    {{-- Cover Image --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image *</label>
                        <template x-if="!formData.coverImage.preview">
                            <div @click="$refs.coverInput.click()" class="flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center transition-colors hover:border-orange-400 hover:bg-orange-50/30 cursor-pointer">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-semibold text-gray-700">Choose file or drag and drop</span>
                                    <span class="text-xs text-gray-500">Image (max 4MB)</span>
                                </div>
                            </div>
                        </template>
                        <template x-if="formData.coverImage.preview">
                            <div class="space-y-3">
                                <div class="relative w-full aspect-video rounded-lg overflow-hidden border-2 border-gray-200 bg-gray-100">
                                    <img :src="formData.coverImage.preview" alt="Cover preview" class="object-cover w-full h-full">
                                </div>
                                <button type="button" @click="removeCoverImage()" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">Remove Image</button>
                            </div>
                        </template>
                        <input type="file" x-ref="coverInput" @change="handleCoverImage($event)" accept="image/*" class="hidden">
                        <x-ui.error name="cover_image_url" />
                    </div>

                    {{-- Gallery Images --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images (Max 3)</label>
                        <div class="grid grid-cols-3 gap-4 mb-3" x-show="formData.galleryImages.length > 0">
                            <template x-for="(img, index) in formData.galleryImages" :key="index">
                                <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-gray-200 bg-gray-100">
                                    <img :src="img.preview" alt="Gallery" class="object-cover w-full h-full">
                                    <button type="button" @click="removeGalleryImage(index)" class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="$refs.galleryInput.click()" x-show="formData.galleryImages.length < 3" class="w-full h-12 px-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-400 hover:bg-orange-50/30 text-gray-600 hover:text-orange-600 font-medium transition-colors">+ Add Gallery Image (<span x-text="formData.galleryImages.length"></span>/3)</button>
                        <input type="file" x-ref="galleryInput" @change="handleGalleryImage($event)" accept="image/*" class="hidden">
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                {{-- SECTION 4: Highlights & Notes --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Highlights & Notes</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trip Highlights *</label>
                        <template x-for="(highlight, index) in formData.highlights" :key="index">
                            <div class="flex flex-col sm:flex-row gap-2 mb-2">
                                <input type="text" x-model="formData.highlights[index]" placeholder="Enter a highlight" class="flex-1 min-h-12 h-12 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <button type="button" @click="formData.highlights.splice(index, 1)" class="px-4 min-h-12 h-12 text-red-600 hover:bg-red-50 rounded-lg font-medium whitespace-nowrap">Remove</button>
                            </div>
                        </template>
                        <button type="button" @click="formData.highlights.push('')" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">+ Add Highlight</button>
                        <x-ui.error name="highlights" class="mt-2" />
                    </div>

                    <x-ui.field>
                        <x-ui.label>Important Notes</x-ui.label>
                        <textarea
                            name="notes"
                            placeholder="Any important notes for travelers (e.g., what to bring, requirements)..."
                            rows="3"
                            class="inline-block p-2 w-full text-base sm:text-sm text-neutral-800 placeholder-neutral-400 bg-white shadow-sm border rounded-lg border-black/10 focus:border-black/15 focus:ring-2 focus:ring-neutral-900/15 focus:ring-offset-0 focus:outline-none transition-colors duration-200 resize-none"
                            @input="$el.closest('[data-slot=field]')?.querySelector('[data-slot=error]')?.remove()"
                        >{{ old('notes') }}</textarea>
                        <x-ui.error name="notes" />
                    </x-ui.field>
                </div>

                <hr class="my-8 border-gray-200">

                {{-- SECTION 5: Inclusions & Exclusions --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Inclusions & Exclusions</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Inclusions *</label>
                            <template x-for="(inclusion, index) in formData.inclusions" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" x-model="formData.inclusions[index]" placeholder="What's included" class="flex-1 h-12 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <button type="button" @click="formData.inclusions.splice(index, 1)" class="px-3 text-red-600 hover:bg-red-50 rounded-lg">×</button>
                                </div>
                            </template>
                            <button type="button" @click="formData.inclusions.push('')" class="text-green-600 hover:text-green-700 text-sm font-semibold">+ Add Inclusion</button>
                            <x-ui.error name="inclusions" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Exclusions</label>
                            <template x-for="(exclusion, index) in formData.exclusions" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" x-model="formData.exclusions[index]" placeholder="What's not included" class="flex-1 h-12 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <button type="button" @click="formData.exclusions.splice(index, 1)" class="px-3 text-red-600 hover:bg-red-50 rounded-lg">×</button>
                                </div>
                            </template>
                            <button type="button" @click="formData.exclusions.push('')" class="text-red-600 hover:text-red-700 text-sm font-semibold">+ Add Exclusion</button>
                        </div>
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                {{-- SECTION 6: Itinerary --}}
                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Day-by-Day Itinerary *</h2>

                    <template x-for="(day, dayIndex) in formData.itinerary" :key="dayIndex">
                        <div class="border-2 border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-semibold text-gray-700 text-lg" x-text="'Day ' + (dayIndex + 1)"></h3>
                                <button type="button" @click="formData.itinerary.splice(dayIndex, 1)" class="text-red-600 hover:bg-red-100 px-3 py-1 rounded font-medium text-sm">Remove Day</button>
                            </div>
                            <template x-for="(activity, actIndex) in day.activities" :key="actIndex">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" x-model="day.activities[actIndex]" placeholder="Activity description" class="flex-1 h-12 px-3 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    <button type="button" @click="day.activities.splice(actIndex, 1)" class="px-3 text-red-600 hover:bg-red-100 rounded">×</button>
                                </div>
                            </template>
                            <button type="button" @click="day.activities.push('')" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">+ Add Activity</button>
                        </div>
                    </template>
                    <button type="button" @click="formData.itinerary.push({ activities: [''] })" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">+ Add Day</button>
                    <x-ui.error name="itinerary" class="mt-2" />
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-between pt-8 border-t-2 border-gray-200">
                    <button type="button" @click="resetForm()" class="px-4 py-2 md:px-6 md:py-2.5 lg:px-8 lg:py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 font-semibold text-sm md:text-base text-gray-700 transition-all duration-200 focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 md:px-6 md:py-2.5 lg:px-8 lg:py-3 bg-linear-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-semibold text-sm md:text-base transition-all duration-200 shadow-lg hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transform hover:scale-[1.02]">
                        Save & Continue
                    </button>
                </div>
            </form>
        </div>

        {{-- Loading Overlay --}}
        <div x-show="isUploading" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-8 text-center">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-b-4 border-orange-600 mb-4"></div>
                <p class="text-lg font-semibold text-gray-700" x-text="uploadingMessage"></p>
            </div>
        </div>

        {{-- Clear Form Confirmation Dialog --}}
        <div x-show="showClearDialog" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
                <div class="flex items-center gap-4 mb-4">
                    <div class="shrink-0 w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Clear Form?</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to clear this form? All entered data will be lost and cannot be recovered.</p>
                <div class="flex gap-3 justify-end">
                    <button @click="showClearDialog = false" type="button" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100 hover:border-gray-400 font-semibold text-gray-700 transition-all duration-200">
                        Cancel
                    </button>
                    <button @click="clearForm()" type="button" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-semibold transition-all duration-200">
                        Clear Form
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

@php
    $oldHighlights = old('highlights') ? json_decode(old('highlights'), true) : [''];
    $oldInclusions = old('inclusions') ? json_decode(old('inclusions'), true) : [''];
    $oldExclusions = old('exclusions') ? json_decode(old('exclusions'), true) : [''];
    $oldTransportOptions = old('transport_options') ? json_decode(old('transport_options'), true) : [];
    $oldItinerary = old('itinerary') ? json_decode(old('itinerary'), true) : [['activities' => ['']]];
@endphp

<script>
function tripFormData() {
    return {
        isUploading: false,
        uploadingMessage: '',
        showClearDialog: false,
        // Alpine variables for select and other controls
        type: '{{ old('type', '') }}',

        // Data for file uploads and dynamic arrays (Alpine.js managed)
        formData: {
            coverImage: { file: null, preview: null },
            galleryImages: [],
            highlights: @json($oldHighlights),
            inclusions: @json($oldInclusions),
            exclusions: @json($oldExclusions),
            transport_options: @json($oldTransportOptions),
            itinerary: @json($oldItinerary),
        },

        init() {
            // If there are old values but they're empty arrays, set defaults
            if (this.formData.highlights.length === 0) this.formData.highlights = [''];
            if (this.formData.inclusions.length === 0) this.formData.inclusions = [''];
            if (this.formData.exclusions.length === 0) this.formData.exclusions = [''];

            // Convert itinerary from object format {"Day 1": [...]} back to array format
            if (this.formData.itinerary && !Array.isArray(this.formData.itinerary)) {
                const itineraryArray = Object.keys(this.formData.itinerary).map(day => ({
                    activities: this.formData.itinerary[day]
                }));
                this.formData.itinerary = itineraryArray.length > 0 ? itineraryArray : [{ activities: [''] }];
            } else if (this.formData.itinerary.length === 0) {
                this.formData.itinerary = [{ activities: [''] }];
            }

            // Convert transport_options from object format back to array format
            if (this.formData.transport_options && !Array.isArray(this.formData.transport_options)) {
                const transportArray = Object.keys(this.formData.transport_options).map(name => ({
                    name: name,
                    from: this.formData.transport_options[name].from || '',
                    price: this.formData.transport_options[name].price || ''
                }));
                this.formData.transport_options = transportArray;
            }
        },

        handleCoverImage(event) {
            const file = event.target.files[0];
            if (file && file.size <= 4 * 1024 * 1024) {
                this.formData.coverImage = { file: file, preview: URL.createObjectURL(file) };
            } else {
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', content: 'Image must be less than 4MB' }}));
            }
        },

        removeCoverImage() {
            this.formData.coverImage = { file: null, preview: null };
            this.$refs.coverInput.value = '';
        },

        handleGalleryImage(event) {
            const file = event.target.files[0];
            if (file && file.size <= 4 * 1024 * 1024) {
                this.formData.galleryImages.push({ file: file, preview: URL.createObjectURL(file) });
                this.$refs.galleryInput.value = '';
            } else {
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', content: 'Image must be less than 4MB' }}));
            }
        },

        removeGalleryImage(index) {
            this.formData.galleryImages.splice(index, 1);
        },

        async handleFormSubmit(event) {
            const form = this.$refs.tripForm;

            // Validate required fields before showing loading modal
            const title = form.querySelector('[name="title"]').value.trim();
            const destination = form.querySelector('[name="destination"]').value.trim();
            const hotel_name = form.querySelector('[name="hotel_name"]').value.trim();
            const type = form.querySelector('[name="type"]').value;
            const duration_days = form.querySelector('[name="duration_days"]').value;
            const description = form.querySelector('[name="description"]').value.trim();
            const base_price_per_person = form.querySelector('[name="base_price_per_person"]').value;

            // Check if required fields are filled
            if (!title || !destination || !hotel_name || !type || !duration_days || !description || !base_price_per_person) {
                // Let the form submit normally so Laravel can show validation errors
                form.submit();
                return;
            }

            // Check if cover image is selected
            if (!this.formData.coverImage.file) {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'error', content: 'Please select a cover image' }
                }));
                return;
            }

            // Show loading state only if all required fields are filled
            this.isUploading = true;

            try {
                // Upload cover image only if one is selected
                if (this.formData.coverImage.file) {
                    this.uploadingMessage = 'Uploading cover image...';
                    const coverImageUrl = await this.uploadToCloudinary(this.formData.coverImage.file);
                    this.addHiddenInput('cover_image_url', coverImageUrl);
                }

                // Upload gallery images
                const galleryUrls = [];
                for (let i = 0; i < this.formData.galleryImages.length; i++) {
                    this.uploadingMessage = `Uploading gallery image ${i + 1}/${this.formData.galleryImages.length}...`;
                    const url = await this.uploadToCloudinary(this.formData.galleryImages[i].file);
                    galleryUrls.push(url);
                }

                if (galleryUrls.length > 0) {
                    this.addHiddenInput('gallery', JSON.stringify(galleryUrls));
                }

                this.uploadingMessage = 'Creating trip package...';

                // Add hidden inputs for arrays
                const validHighlights = this.formData.highlights.filter(h => h.trim());
                const validInclusions = this.formData.inclusions.filter(i => i.trim());
                const validExclusions = this.formData.exclusions.filter(e => e.trim());

                this.addHiddenInput('highlights', JSON.stringify(validHighlights));
                this.addHiddenInput('inclusions', JSON.stringify(validInclusions));
                this.addHiddenInput('exclusions', JSON.stringify(validExclusions));

                // Add transport options as JSON
                const transportOptions = this.formData.transport_options.reduce((acc, t) => {
                    if (t.name && t.price) {
                        acc[t.name] = { price: parseFloat(t.price) };
                        if (t.from) acc[t.name].from = t.from;
                    }
                    return acc;
                }, {});
                this.addHiddenInput('transport_options', JSON.stringify(transportOptions));

                // Add itinerary as JSON
                const itinerary = this.formData.itinerary.reduce((acc, day, index) => {
                    const activities = day.activities.filter(a => a.trim());
                    if (activities.length > 0) {
                        acc[`Day ${index + 1}`] = activities;
                    }
                    return acc;
                }, {});
                this.addHiddenInput('itinerary', JSON.stringify(itinerary));

                // Submit form traditionally - Laravel will validate and redirect back with errors if needed
                form.submit();

            } catch (error) {
                console.error(error);
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type: 'error', content: 'Failed to upload images. Please try again.' }
                }));
                this.isUploading = false;
            }
        },

        addHiddenInput(name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            this.$refs.tripForm.appendChild(input);
        },

        resetForm() {
            this.showClearDialog = true;
        },

        clearForm() {
            this.showClearDialog = false;
            location.reload();
        },

        async uploadToCloudinary(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('upload_preset', '{{ config('cloudinary.upload_preset') }}');
            formData.append('folder', 'triply');

            const response = await fetch('https://api.cloudinary.com/v1_1/{{ config('cloudinary.cloud_name') }}/image/upload', {
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

<style>
[x-cloak] { display: none !important; }
</style>
@endsection

