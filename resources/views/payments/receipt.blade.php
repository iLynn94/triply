@extends('layouts.main')

@section('title', 'Payment Receipt - Booking #' . $booking->id)

@section('content')
<div class="min-h-screen bg-gray-50 py-8 md:py-12">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('bookings') }}" class="inline-flex items-center gap-2 text-orange-600 hover:text-orange-700 mb-4 transition-colors">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    Back to Bookings
                </a>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">Payment Receipt</h1>
            </div>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium print:hidden">
                <x-heroicon-o-printer class="w-5 h-5" />
                Print Receipt
            </button>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg print:hidden">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                    <span class="text-green-700">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Receipt Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">

            {{-- Header Section --}}
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-8 py-8 text-white">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold mb-2">Triply</h2>
                        <p class="text-orange-100">Travel & Adventure</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg">
                        <p class="text-sm text-orange-100 mb-1">Payment Status</p>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-check-circle class="w-6 h-6" />
                            <span class="text-xl font-bold">PAID</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Receipt Details --}}
            <div class="p-8 space-y-8">

                {{-- Receipt Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Receipt Information</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Receipt No:</span>
                                <span class="font-semibold text-gray-900 ml-2">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Booking ID:</span>
                                <span class="font-semibold text-gray-900 ml-2">#{{ $booking->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Payment Date:</span>
                                <span class="font-semibold text-gray-900 ml-2">{{ $booking->updated_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Payment Time:</span>
                                <span class="font-semibold text-gray-900 ml-2">{{ $booking->updated_at->format('h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-3">Customer Information</h3>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold text-gray-900 ml-2">{{ $booking->user->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <span class="font-semibold text-gray-900 ml-2">{{ $booking->user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trip Details --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Trip Information</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex flex-col sm:flex-row gap-4">
                            {{-- Trip Image --}}
                            <div class="w-full sm:w-32 h-32 rounded-lg overflow-hidden flex-shrink-0">
                                <img
                                    src="{{ $booking->trip->cover_image_url }}"
                                    alt="{{ $booking->trip->title }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>

                            {{-- Trip Details --}}
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 mb-3">{{ $booking->trip->title }}</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-map-pin class="w-4 h-4 text-orange-600 flex-shrink-0" />
                                        <span class="text-gray-700">{{ $booking->trip->destination }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-calendar class="w-4 h-4 text-orange-600 flex-shrink-0" />
                                        <span class="text-gray-700">{{ $booking->start_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-clock class="w-4 h-4 text-orange-600 flex-shrink-0" />
                                        <span class="text-gray-700">{{ $booking->trip->duration_days }} {{ Str::plural('Day', $booking->trip->duration_days) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-users class="w-4 h-4 text-orange-600 flex-shrink-0" />
                                        <span class="text-gray-700">{{ $booking->number_of_adults }} Adults, {{ $booking->number_of_children }} Children</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Summary --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700">Adults ({{ $booking->number_of_adults }} × Ksh {{ number_format($booking->trip->base_price_per_person, 0) }})</span>
                            <span class="font-semibold text-gray-900">Ksh {{ number_format($booking->number_of_adults * $booking->trip->base_price_per_person, 0) }}</span>
                        </div>

                        @if($booking->number_of_children > 0)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700">Children ({{ $booking->number_of_children }} × Ksh {{ number_format($booking->trip->base_price_per_person * (1 - $booking->trip->child_discount_percent / 100), 0) }})</span>
                            <span class="font-semibold text-gray-900">Ksh {{ number_format($booking->number_of_children * $booking->trip->base_price_per_person * (1 - $booking->trip->child_discount_percent / 100), 0) }}</span>
                        </div>
                        @endif

                        @if($booking->selected_transport && isset($booking->trip->transport_options[$booking->selected_transport]))
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700">Transport ({{ $booking->selected_transport }})</span>
                            <span class="font-semibold text-gray-900">Ksh {{ number_format($booking->trip->transport_options[$booking->selected_transport]['price'], 0) }}</span>
                        </div>
                        @endif

                        {{-- Total --}}
                        <div class="flex justify-between items-center py-4 bg-orange-50 border border-orange-200 rounded-lg px-4 mt-4">
                            <span class="text-lg font-bold text-gray-900">Total Amount Paid</span>
                            <span class="text-2xl font-bold text-orange-600">Ksh {{ number_format($booking->total_fee, 0) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer Note --}}
                <div class="pt-6 border-t border-gray-200">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
                            <div class="text-sm text-blue-900">
                                <p class="font-semibold mb-1">Important Information</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-800">
                                    <li>This receipt confirms your payment for the booking.</li>
                                    <li>Please keep this receipt for your records.</li>
                                    <li>For any queries, please contact support with your booking ID.</li>
                                    <li>Check your email for additional booking confirmation details.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Thank You Message --}}
                <div class="text-center pt-6">
                    <p class="text-gray-600 text-lg">Thank you for choosing <span class="font-bold text-orange-600">Triply</span>!</p>
                    <p class="text-gray-500 text-sm mt-2">We look forward to making your trip memorable.</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3 print:hidden">
            <a href="{{ route('bookings') }}" class="flex-1 text-center px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors font-medium">
                Back to My Bookings
            </a>
            <a href="{{ url('/') }}" class="flex-1 text-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                Browse More Trips
            </a>
        </div>
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        body {
            background: white;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endsection
