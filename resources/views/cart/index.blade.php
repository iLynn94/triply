@extends('layouts.main')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6 border-b pb-2">
                Pending Bookings (Cart)
            </h2>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($cartItems->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 text-lg">You have no pending trips.</p>
                    <a href="{{ url('/') }}" class="text-orange-600 hover:text-orange-800 font-bold mt-2 inline-block underline">
                        Browse Packages
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-200 text-left">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Trip Destination</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Travelers</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Estimated Cost</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-5 text-sm bg-white">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap font-bold">
                                                {{ $item->trip->destination ?? 'Trip #'.$item->trip_id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 text-sm bg-white">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $item->people }} people</p>
                                </td>
                                <td class="px-5 py-5 text-sm bg-white">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold text-green-600">
                                        Ksh {{ number_format($item->subtotal, 2) }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 text-sm bg-white">
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-between items-center border-t pt-4">
                    <div class="text-2xl font-bold text-gray-800">Total: Ksh {{ number_format($total, 2) }}</div>
                    <button class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded shadow transition duration-300">
                        Proceed to Payment
                    </button>
                </div>
            @endif
        </div>