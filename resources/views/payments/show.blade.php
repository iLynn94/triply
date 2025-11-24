@extends('layouts.main')

@section('title', 'Payment for Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gray-50 py-8 md:py-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('bookings') }}" class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 mb-4 transition-colors">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                Back to Bookings
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">Payment for Booking</h1>
            <p class="text-gray-600 mt-2">Complete your payment to confirm your booking</p>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                    <span class="text-green-700">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-600" />
                    <span class="text-red-700">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600" />
                    <span class="text-blue-700">{{ session('info') }}</span>
                </div>
            </div>
        @endif

        @if(request()->get('payment_success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                    <span class="text-green-700">Payment successful! Your booking is confirmed.</span>
                </div>
            </div>
        @endif

        @if(request()->get('payment_canceled'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-600" />
                    <span class="text-yellow-700">Payment was cancelled. You can try again.</span>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Left: Booking Details (Takes 2 columns) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Trip Information Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Trip Details</h2>

                        <div class="flex flex-col sm:flex-row gap-4">
                            {{-- Trip Image --}}
                            <div class="w-full sm:w-40 h-40 rounded-lg overflow-hidden flex-shrink-0">
                                <img
                                    src="{{ $booking->trip->cover_image_url }}"
                                    alt="{{ $booking->trip->title }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>

                            {{-- Trip Info --}}
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $booking->trip->title }}</h3>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-map-pin class="w-4 h-4 text-orange-600" />
                                        <span>{{ $booking->trip->destination }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-calendar class="w-4 h-4 text-orange-600" />
                                        <span>Departure: {{ $booking->start_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-clock class="w-4 h-4 text-orange-600" />
                                        <span>{{ $booking->trip->duration_days }} {{ Str::plural('Day', $booking->trip->duration_days) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Booking Details Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Booking Information</h2>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Booking ID</p>
                            <p class="font-semibold text-gray-900">#{{ $booking->id }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Status</p>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{
                                $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                ($booking->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')
                            }}">
                                {{ strtoupper($booking->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Adults</p>
                            <p class="font-semibold text-gray-900">{{ $booking->number_of_adults }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Children</p>
                            <p class="font-semibold text-gray-900">{{ $booking->number_of_children }}</p>
                        </div>
                        @if($booking->selected_transport)
                        <div class="col-span-2">
                            <p class="text-gray-500 mb-1">Transport</p>
                            <p class="font-semibold text-gray-900">{{ $booking->selected_transport }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">Price Breakdown</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Adults ({{ $booking->number_of_adults }} × Ksh {{ number_format($booking->trip->base_price_per_person, 0) }})</span>
                                <span class="text-gray-900 font-medium">Ksh {{ number_format($booking->number_of_adults * $booking->trip->base_price_per_person, 0) }}</span>
                            </div>
                            @if($booking->number_of_children > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Children ({{ $booking->number_of_children }} × Ksh {{ number_format($booking->trip->base_price_per_person * (1 - $booking->trip->child_discount_percent / 100), 0) }})</span>
                                <span class="text-gray-900 font-medium">Ksh {{ number_format($booking->number_of_children * $booking->trip->base_price_per_person * (1 - $booking->trip->child_discount_percent / 100), 0) }}</span>
                            </div>
                            @endif
                            @if($booking->selected_transport && isset($booking->trip->transport_options[$booking->selected_transport]))
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transport ({{ $booking->selected_transport }})</span>
                                <span class="text-gray-900 font-medium">Ksh {{ number_format($booking->trip->transport_options[$booking->selected_transport]['price'], 0) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Payment Form (Sticky) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 lg:sticky lg:top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">Complete Payment</h2>

                    {{-- Total Amount --}}
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Total Amount</span>
                            <span class="text-3xl font-bold text-orange-600">Ksh {{ number_format($booking->total_fee, 0) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('payments.booking.process', $booking) }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- M-Pesa Phone Input --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">M-Pesa Phone Number</label>
                            <input
                                type="text"
                                name="phone_number"
                                placeholder="254712345678"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                value="254"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500">Enter your phone number for M-Pesa payment</p>
                        </div>

                        {{-- Payment Methods --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <div class="space-y-2">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="mpesa" class="peer sr-only" checked>
                                    <div class="flex items-center justify-between border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold">M</div>
                                            <span class="font-semibold text-gray-900 peer-checked:text-green-700">M-PESA</span>
                                        </div>
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" class="peer sr-only">
                                    <div class="flex items-center justify-between border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                                <x-heroicon-o-credit-card class="w-6 h-6 text-white" />
                                            </div>
                                            <span class="font-semibold text-gray-900 peer-checked:text-blue-700">Card</span>
                                        </div>
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="w-full bg-orange-600 text-white py-3.5 rounded-lg font-bold hover:bg-orange-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                        >
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                            Pay Ksh {{ number_format($booking->total_fee, 0) }}
                        </button>
                    </form>

                    {{-- Security Note --}}
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-start gap-2 text-xs text-gray-500">
                            <x-heroicon-o-shield-check class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" />
                            <p>Your payment is secure and encrypted. We never store your payment details.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
