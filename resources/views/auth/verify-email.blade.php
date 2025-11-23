@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="text-center">
    <svg class="w-16 h-16 mx-auto mb-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
    </svg>

    <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email Address</h2>

    <p class="text-gray-600 mb-6">
        Before proceeding, please check your email for a verification link.
        <br>
        If you didn't receive the email, click the button below.
    </p>

    <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
        @csrf
        <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors font-medium">
            Resend Verification Email
        </button>
    </form>

    <p class="text-sm text-gray-500">
        <a href="{{ route('profile') }}" class="text-orange-600 hover:text-orange-800 underline">
            Go to Profile
        </a>
    </p>
</div>
@endsection
