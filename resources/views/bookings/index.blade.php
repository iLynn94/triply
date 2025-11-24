@extends('layouts.main')

@section('title', 'Bookings')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-8 md:px-8 lg:px-16 xl:px-32 min-h-screen" x-data="bookingsPageData()">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">My Bookings</h1>
        <p class="text-gray-600">Manage your trip bookings and leave reviews</p>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Bookings Grid --}}
    @if($bookings->count() > 0)
        <div class="space-y-6">
            @foreach($bookings as $booking)
                @php
                    $isUpcoming = $booking->start_date->isFuture();
                    $isPast = $booking->start_date->isPast();
                    $canRate = $isPast && in_array($booking->status, ['confirmed']) && in_array($booking->payment_status, ['paid']);
                    $userRating = $booking->trip->ratings->where('user_id', auth()->id())->first();
                @endphp

                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">

                        {{-- Left: Trip Image & Basic Info --}}
                        <div class="lg:col-span-1">
                            <div class="relative h-48 lg:h-full min-h-[250px] rounded-lg overflow-hidden mb-4 lg:mb-0">
                                <img
                                    src="{{ $booking->trip->cover_image_url }}"
                                    alt="{{ $booking->trip->title }}"
                                    class="w-full h-full object-cover"
                                >
                                {{-- Status Badge --}}
                                <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold {{
                                    $isUpcoming ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white'
                                }}">
                                    {{ $isUpcoming ? 'UPCOMING' : 'COMPLETED' }}
                                </div>
                            </div>
                        </div>

                        {{-- Middle: Trip & Booking Details --}}
                        <div class="lg:col-span-1 space-y-4">
                            <div>
                                <a href="{{ url('/trip/' . $booking->trip->id) }}">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2 hover:text-orange-400">{{ $booking->trip->title }}</h2>
                                </a>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-map-pin class="w-4 h-4 text-orange-600" />
                                        <span>{{ $booking->trip->destination }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-calendar class="w-4 h-4 text-orange-600" />
                                        <span>{{ $booking->start_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-clock class="w-4 h-4 text-orange-600" />
                                        <span>{{ $booking->trip->duration_days }} {{ Str::plural('Day', $booking->trip->duration_days) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Booking Details</h3>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-gray-500">Adults</p>
                                        <p class="font-semibold text-gray-900" id="adults-display-{{ $booking->id }}">{{ $booking->number_of_adults }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Children</p>
                                        <p class="font-semibold text-gray-900" id="children-display-{{ $booking->id }}">{{ $booking->number_of_children }}</p>
                                    </div>
                                    @if($booking->selected_transport)
                                    <div class="col-span-2">
                                        <p class="text-gray-500">Transport</p>
                                        <p class="font-semibold text-gray-900" id="transport-display-{{ $booking->id }}">{{ $booking->selected_transport }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Booking Status --}}
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{
                                    $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                    ($booking->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')
                                }}">
                                    <span class="w-2 h-2 rounded-full {{
                                        $booking->status === 'confirmed' ? 'bg-green-500' :
                                        ($booking->status === 'cancelled' ? 'bg-red-500' : 'bg-yellow-500')
                                    }}"></span>
                                    {{ strtoupper($booking->status) }}
                                </div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{
                                    $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' :
                                    ($booking->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')
                                }}">
                                    <span class="w-2 h-2 rounded-full {{
                                        $booking->payment_status === 'paid' ? 'bg-green-500' :
                                        ($booking->payment_status === 'failed' ? 'bg-red-500' : 'bg-yellow-500')
                                    }}"></span>
                                    PAYMENT: {{ strtoupper($booking->payment_status) }}
                                </div>
                            </div>

                            {{-- User Rating Display --}}
                            @if($userRating)
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-xs font-semibold text-orange-900">Your Review</p>
                                    <button
                                        @click="openRatingDialog({{ $booking->id }})"
                                        class="text-orange-600 hover:text-orange-700 transition-colors"
                                        title="Edit Review"
                                    >
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </button>
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $userRating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                @if($userRating->comment)
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $userRating->comment }}</p>
                                @endif
                            </div>
                            @endif
                        </div>

                        {{-- Right: Actions & Price --}}
                        <div class="lg:col-span-1 flex flex-col justify-between">
                            {{-- Price --}}
                            <div class="mb-6">
                                <p class="text-sm text-gray-500 mb-1">Total Amount</p>
                                <p class="text-3xl font-bold text-orange-600" id="price-display-{{ $booking->id }}">
                                    Ksh {{ number_format($booking->total_fee, 0) }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            <div class="space-y-3">

                                {{-- Edit Booking (Upcoming Only and Not Paid) --}}
                                @if($isUpcoming)
                                    @if($booking->payment_status !== 'paid')
                                        <button
                                            @click="openEditDialog({{ $booking->id }})"
                                            class="w-full px-4 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium text-sm"
                                        >
                                            <div class="flex items-center justify-center gap-2">
                                                <x-heroicon-o-pencil class="w-5 h-5" />
                                                Edit Booking
                                            </div>
                                        </button>
                                    @else
                                        <button
                                            disabled
                                            class="w-full px-4 py-2.5 border-2 border-gray-300 text-gray-400 rounded-lg cursor-not-allowed font-medium text-sm opacity-60"
                                            title="Paid bookings cannot be edited"
                                        >
                                            <div class="flex items-center justify-center gap-2">
                                                <x-heroicon-o-lock-closed class="w-5 h-5" />
                                                Edit Booking
                                            </div>
                                        </button>
                                    @endif
                                @endif

                                {{-- Delete Booking (Unpaid Only) --}}
                                @if($booking->payment_status !== 'paid')
                                    <button
                                        @click="openDeleteDialog({{ $booking->id }})"
                                        class="w-full px-4 py-2.5 border-2 border-red-300 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-400 transition-colors font-medium text-sm"
                                    >
                                        <div class="flex items-center justify-center gap-2">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                            Cancel Booking
                                        </div>
                                    </button>
                                @endif

                                {{-- Payment Link --}}
                                @if($booking->payment_status !== 'paid')
                                    <a href="{{ route('payments.show', $booking) }}"
                                       class="block w-full text-center px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                                        <div class="flex items-center justify-center gap-2">
                                            <x-heroicon-o-credit-card class="w-5 h-5" />
                                            Make Payment
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ route('payments.receipt', $booking) }}"
                                       class="block w-full text-center px-4 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium text-sm">
                                        <div class="flex items-center justify-center gap-2">
                                            <x-heroicon-o-check-circle class="w-5 h-5" />
                                            View Payment Receipt
                                        </div>
                                    </a>
                                @endif

                                {{-- Rating/Review (Past, Confirmed & Paid trips) --}}
                                @if($canRate && !$userRating)
                                    <button
                                        @click="openRatingDialog({{ $booking->id }})"
                                        class="w-full px-4 py-2.5 bg-orange-600 text-white hover:bg-orange-700 rounded-lg transition-colors font-medium text-sm"
                                    >
                                        <div class="flex items-center justify-center gap-2">
                                            <x-heroicon-o-star class="w-5 h-5" />
                                            Rate Trip
                                        </div>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
            <x-heroicon-o-calendar class="w-20 h-20 text-gray-400 mx-auto mb-4" />
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Bookings Yet</h3>
            <p class="text-gray-600 mb-6">Start exploring amazing trips and create your first booking!</p>
            <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                Browse Trips
            </a>
        </div>
    @endif

    {{-- Edit Booking Dialog --}}
    @foreach($bookings as $booking)
        @if($booking->start_date->isFuture() && $booking->payment_status !== 'paid')
            <div x-show="editDialogId === {{ $booking->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="closeEditDialog()">
                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Edit Booking Details</h3>
                        <button @click="closeEditDialog()" class="text-gray-400 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
                        </button>
                    </div>

                    <form action="{{ route('bookings.update', $booking) }}" method="POST" id="editForm-{{ $booking->id }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adults *</label>
                            <input
                                type="number"
                                name="number_of_adults"
                                value="{{ $booking->number_of_adults }}"
                                min="1"
                                required
                                onchange="updateBookingPrice({{ $booking->id }}, {{ $booking->trip->base_price_per_person }}, {{ $booking->trip->child_discount_percent }}, {{ json_encode($booking->trip->transport_options ?? []) }})"
                                id="edit-adults-{{ $booking->id }}"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                            <input
                                type="number"
                                name="number_of_children"
                                value="{{ $booking->number_of_children }}"
                                min="0"
                                onchange="updateBookingPrice({{ $booking->id }}, {{ $booking->trip->base_price_per_person }}, {{ $booking->trip->child_discount_percent }}, {{ json_encode($booking->trip->transport_options ?? []) }})"
                                id="edit-children-{{ $booking->id }}"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Travel Date *</label>
                            <input
                                type="date"
                                name="start_date"
                                value="{{ $booking->start_date->format('Y-m-d') }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                            />
                        </div>

                        @if($booking->trip->transport_options && count($booking->trip->transport_options) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Transport Option</label>
                            <select
                                name="selected_transport"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                                onchange="updateBookingPrice({{ $booking->id }}, {{ $booking->trip->base_price_per_person }}, {{ $booking->trip->child_discount_percent }}, {{ json_encode($booking->trip->transport_options ?? []) }})"
                                id="edit-transport-{{ $booking->id }}"
                            >
                                <option value="">No transport</option>
                                @foreach($booking->trip->transport_options as $name => $details)
                                <option value="{{ $name }}" {{ $booking->selected_transport === $name ? 'selected' : '' }} data-price="{{ $details['price'] }}">
                                    {{ $name }} (+Ksh {{ number_format($details['price'], 0) }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700 font-medium">Estimated Total</span>
                                <span class="text-2xl font-bold text-orange-600" id="edit-price-{{ $booking->id }}">
                                    Ksh {{ number_format($booking->total_fee, 0) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end pt-4">
                            <button type="button" @click="closeEditDialog()" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100 font-semibold text-gray-700 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-semibold transition-all">
                                Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Delete Booking Dialog --}}
    @foreach($bookings as $booking)
        @if($booking->payment_status !== 'paid')
            <div x-show="deleteDialogId === {{ $booking->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="closeDeleteDialog()">
                <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                            <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-red-600" />
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Cancel Booking?</h3>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">Are you sure you want to cancel this booking for <strong>{{ $booking->trip->title }}</strong>? This action cannot be undone.</p>
                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="closeDeleteDialog()" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100 font-semibold text-gray-700 transition-all">
                            Keep Booking
                        </button>
                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition-all">
                                Cancel Booking
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Edit Rating Dialog --}}
    @foreach($bookings as $booking)
        @php
            $isPast = $booking->start_date->isPast();
            $canRate = $isPast && in_array($booking->status, ['confirmed']) && in_array($booking->payment_status, ['paid']);
            $userRating = $booking->trip->ratings->where('user_id', auth()->id())->first();
        @endphp

        @if($canRate)
            <div x-show="ratingDialogId === {{ $booking->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" @click.self="closeRatingDialog()">
                <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $userRating ? 'Edit Your Review' : 'Rate This Trip' }}</h3>
                        <button @click="closeRatingDialog()" class="text-gray-400 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="w-6 h-6" />
                        </button>
                    </div>

                    <form action="{{ route('rating.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="trip_id" value="{{ $booking->trip->id }}">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                            <div class="flex items-center gap-2" id="rating-dialog-stars-{{ $booking->id }}">
                                @for($i = 1; $i <= 5; $i++)
                                    <button
                                        type="button"
                                        onclick="setDialogRating({{ $booking->id }}, {{ $i }})"
                                        class="rating-dialog-star focus:outline-none transition-colors"
                                        data-rating="{{ $i }}"
                                    >
                                        <svg class="w-8 h-8 {{ $userRating && $i <= $userRating->rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-dialog-input-{{ $booking->id }}" value="{{ $userRating->rating ?? 5 }}" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comment (Optional)</label>
                            <textarea
                                name="comment"
                                placeholder="Share your experience..."
                                rows="4"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 resize-none"
                            >{{ $userRating->comment ?? '' }}</textarea>
                        </div>

                        <div class="flex gap-2 justify-end pt-2">
                            <button type="button" @click="closeRatingDialog()" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100 font-semibold text-gray-700 transition-all">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-semibold transition-all">
                                {{ $userRating ? 'Update Review' : 'Submit Review' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
</div>

{{-- JavaScript for Dynamic Price Calculation and Rating --}}
<script>
    function bookingsPageData() {
        return {
            editDialogId: null,
            deleteDialogId: null,
            ratingDialogId: null,

            openEditDialog(bookingId) {
                this.editDialogId = bookingId;
            },

            closeEditDialog() {
                this.editDialogId = null;
            },

            openDeleteDialog(bookingId) {
                this.deleteDialogId = bookingId;
            },

            closeDeleteDialog() {
                this.deleteDialogId = null;
            },

            openRatingDialog(bookingId) {
                this.ratingDialogId = bookingId;
                // Initialize the rating stars when dialog opens
                setTimeout(() => {
                    const existingRating = document.getElementById(`rating-dialog-input-${bookingId}`)?.value || 5;
                    setDialogRating(bookingId, parseInt(existingRating));
                }, 100);
            },

            closeRatingDialog() {
                this.ratingDialogId = null;
            }
        };
    }
    // Update booking price dynamically in edit form
    function updateBookingPrice(bookingId, basePrice, childDiscount, transportOptions) {
        const adults = parseInt(document.getElementById(`edit-adults-${bookingId}`)?.value || 0);
        const children = parseInt(document.getElementById(`edit-children-${bookingId}`)?.value || 0);
        const transportSelect = document.getElementById(`edit-transport-${bookingId}`);
        const transportPrice = transportSelect ? parseFloat(transportSelect.options[transportSelect.selectedIndex]?.dataset.price || 0) : 0;

        const adultsTotal = adults * basePrice;
        const childrenTotal = children * basePrice * (1 - childDiscount / 100);
        const total = adultsTotal + childrenTotal + transportPrice;

        const priceDisplay = document.getElementById(`edit-price-${bookingId}`);
        if (priceDisplay) {
            priceDisplay.textContent = `Ksh ${total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
        }
    }

    // Set rating stars (for popover)
    function setRating(bookingId, rating) {
        const starsContainer = document.getElementById(`rating-stars-${bookingId}`);
        if (!starsContainer) return;

        const stars = starsContainer.querySelectorAll('.rating-star svg');
        const ratingInput = document.getElementById(`rating-input-${bookingId}`);

        if (ratingInput) {
            ratingInput.value = rating;
        }

        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    // Set rating stars (for dialog)
    function setDialogRating(bookingId, rating) {
        const starsContainer = document.getElementById(`rating-dialog-stars-${bookingId}`);
        if (!starsContainer) return;

        const stars = starsContainer.querySelectorAll('.rating-dialog-star svg');
        const ratingInput = document.getElementById(`rating-dialog-input-${bookingId}`);

        if (ratingInput) {
            ratingInput.value = rating;
        }

        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial rating for all rating forms (popover)
        @foreach($bookings as $booking)
            @if($booking->trip->ratings->where('user_id', auth()->id())->first())
                setRating({{ $booking->id }}, {{ $booking->trip->ratings->where('user_id', auth()->id())->first()->rating }});
            @else
                setRating({{ $booking->id }}, 5);
            @endif
        @endforeach
    });
</script>
@endsection
