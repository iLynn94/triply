@extends('layouts.main')

@section('title', 'Edit Rating')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-md mt-10">

    <h2 class="text-2xl font-semibold mb-6">Edit Rating for: {{ $rating->trip->title }}</h2>

    <form action="{{ route('rating.update', $rating->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label class="block mb-2 font-medium text-gray-700">Rating (1–5)</label>
        <input type="number" name="rating" min="1" max="5"
               value="{{ $rating->rating }}"
               class="w-full border rounded-md p-2 mb-4"
               required>

        <label class="block mb-2 font-medium text-gray-700">Comment</label>
        <textarea name="comment" rows="4"
                  class="w-full border rounded-md p-2 mb-4">{{ $rating->comment }}</textarea>

        <button class="w-full bg-indigo-600 text-black p-3 rounded-md hover:bg-indigo-700">
            Update Rating
        </button>
    </form>

</div>
@endsection
