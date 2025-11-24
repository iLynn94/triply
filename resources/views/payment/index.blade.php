@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Confirm Your Booking</h1>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">{{ session('error') }}</div>
        @endif

        @if($payments->isEmpty())
             <!-- Empty State (Same as before) -->
             <div class="flex flex-col items-center justify-center bg-white rounded-3xl shadow-sm border border-gray-100 p-20 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Your booking list is empty</h2>
                <a href="{{ url('/') }}" class="mt-4 px-8 py-3 bg-orange-600 text-white rounded-full">Explore Trips</a>
            </div>
        @else
            <div class="lg:grid lg:grid-cols-12 lg:gap-12 items-start">
                
                <!-- LEFT: List (Same as before) -->
                <div class="lg:col-span-8 space-y-6">
                    @foreach($payments as $payment)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex justify-between">
                         <div>
                            <h3 class="text-xl font-bold">{{ $payment->trip->destination ?? 'Trip' }}</h3>
                            <p class="text-gray-500">Ref: #{{ $payment->id }}</p>
                         </div>
                         <div class="text-right">
                             <p class="text-xl font-bold">Ksh {{ number_format($payment->amount) }}</p>
                             <form action="{{ route('payment.destroy', $payment->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-red-500 text-sm">Remove</button>
                             </form>
                         </div>
                    </div>
                    @endforeach
                </div>

                <!-- RIGHT: Payment Form -->
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <form action="{{ route('payment.process') }}" method="POST" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                        @csrf
                        <input type="hidden" name="total_amount" value="{{ $total }}">
                        
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Payment Details</h2>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-orange-600">Ksh {{ number_format($total) }}</span>
                        </div>

                        <!-- M-Pesa Phone Input -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number</label>
                            <input type="text" name="phone_number" placeholder="2547..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" value="254">
                        </div>

                        <!-- Payment Methods -->
                        <div class="space-y-3 mb-8">
                            <p class="text-sm font-semibold text-gray-700">Select Method:</p>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="mpesa" class="peer sr-only" checked>
                                    <div class="flex items-center justify-center border border-gray-200 bg-gray-50 py-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700">
                                        M-PESA
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" class="peer sr-only">
                                    <div class="flex items-center justify-center border border-gray-200 bg-gray-50 py-2 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                        Card
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-xl font-bold hover:bg-orange-600 transition">
                            Pay Now
                        </button>
                    </form>
                </div>

            </div>
        @endif
    </div>
</div>
@endsection