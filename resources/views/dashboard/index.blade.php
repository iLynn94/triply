@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        {{-- Display validation errors --}}
        @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Header with view switcher for admin --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back, {{ $view === 'admin' ? 'Admin' : $user->first_name }}</p>
            </div>

            @if($user->isAdmin())
            <form method="GET" action="{{ route('dashboard') }}" class="w-full sm:w-auto">
                <select
                    name="view"
                    onchange="this.form.submit()"
                    class="w-full sm:w-56 h-11 px-4 rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm font-medium cursor-pointer"
                >
                    <option value="admin" {{ $view === 'admin' ? 'selected' : '' }}>Admin View</option>
                    <option value="user" {{ $view === 'user' ? 'selected' : '' }}>Personal View</option>
                </select>
            </form>
            @endif
        </div>

        @if($view === 'admin')
            @include('dashboard.partials.admin-view')
        @else
            @include('dashboard.partials.user-view')
        @endif
    </div>
</div>
@endsection
