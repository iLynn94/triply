@extends('layouts.auth')

@section('title', 'Sign Up')

@section('content')
<form method="POST" action="/sign-up" class="space-y-4">
    @csrf

    {{-- First + Last name row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-ui.field required>
            <x-ui.label>First Name</x-ui.label>
            <x-ui.input wire:model="first_name" name="first_name" type="text" placeholder="Enter your first name" value="{{ old('first_name') }}"/>
            <x-ui.error name="first_name" />
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label>Last Name</x-ui.label>
            <x-ui.input wire:model="last_name" name="last_name" type="text" placeholder="Enter your last name" value="{{ old('last_name') }}"/>
            <x-ui.error name="last_name" />
        </x-ui.field>
    </div>

    {{-- Email --}}
    <x-ui.field required>
        <x-ui.label>Email Address</x-ui.label>
        <x-ui.input wire:model="email" name="email" type="email" placeholder="Enter your email" value="{{ old('email') }}"/>
        <x-ui.error name="email" />
    </x-ui.field>

    {{-- Phone + Citizenship row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-ui.field>
            <x-ui.label>Phone Number</x-ui.label>
            <x-ui.input wire:model="phone_number" name="phone_number" type="tel" placeholder="Enter your phone number" autocomplete="off" value="{{ old('phone_number') }}"/>
            <x-ui.error name="phone_number" />
        </x-ui.field>

        <x-ui.field required>
            <x-ui.label>Citizenship</x-ui.label>
            <x-ui.input wire:model="citizenship" name="citizenship" type="text" placeholder="Enter your citizenship" autocomplete="off" value="{{ old('citizenship') }}"/>
            <x-ui.error name="citizenship" />
        </x-ui.field>
    </div>

    {{-- Password --}}
    <x-ui.field required>
        <x-ui.label>Password</x-ui.label>
        <x-ui.input wire:model="password" id="password" name="password" type="password" placeholder="Enter your password" autocomplete="off" />
        <x-ui.error name="password" />
    </x-ui.field>

    {{-- Confirm Password --}}
    <x-ui.field required>
        <x-ui.label>Confirm Password</x-ui.label>
        <x-ui.input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm your password" autocomplete="off" oninput="this.setCustomValidity('')"/>
        <x-ui.error name="password_confirmation" />
    </x-ui.field>

    <x-ui.button class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700" type="submit">
        Create Account
    </x-ui.button>

    <p class="text-sm text-center mt-2">
        Already have an account? <a href="/sign-in" class="text-orange-600 underline">Sign In</a>
    </p>
</form>
@endsection

<script>
document.getElementById('password_confirmation').addEventListener('input', function () {
    const password = document.getElementById('password').value;
    if (this.value !== password) {
        this.setCustomValidity("Passwords do not match");
    } else {
        this.setCustomValidity("");
    }
});
</script>