@extends('layouts.main')

@section('title', 'Your Ratings')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Your Rated Trips</h1>

    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Trip</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($trips as $trip)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800">{{ $trip->title }}</td>

                        <td class="px-6 py-4 font-semibold">
                            {{ $trip->rating->rating ?? 'Not rated' }} / 5
                        </td>

                        <td class="px-6 py-4">
                            @if ($trip->rating)
                                <a class="text-indigo-600 font-medium hover:text-indigo-800"
                                   href="{{ route('rating.edit', $trip->rating->id) }}">
                                   Edit
                                </a>
                            @else
                                <a class="text-green-600 font-medium hover:text-green-800"
                                   href="{{ route('rating.create', ['trip_id' => $trip->id]) }}">
                                   Rate Now
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
