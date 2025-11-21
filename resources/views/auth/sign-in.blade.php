@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<form method="POST" action="/sign-in" class="space-y-4">
    @csrf

    <x-ui.field required>
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input wire:model="email" type="email" name="email" placeholder="Enter your email" autocomplete="off" value="{{ old('email') }}" />
        <x-ui.error name="email" />
    </x-ui.field>

    <x-ui.field required>
        <x-ui.label>Password</x-ui.label>
            <x-ui.input 
                wire:model="password" 
                placeholder="Password"
                type="password"
                revealable
            />        
            <x-ui.error name="password" />
    </x-ui.field>

    <x-ui.button class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700" type="submit">
        Sign In
    </x-ui.button>

    <p class="text-sm text-center mt-2">
        Don&apos;t have an account? <a href="/sign-up" class="text-orange-600 underline">Register</a>
    </p>
</form>
@endsection
