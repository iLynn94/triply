@extends('layouts.main')

@section('title', 'Wishlist')

@section('content')
    <section class="container mx-auto px-4 py-8 md:px-6 lg:px-12 xl:px-16 min-h-screen">
        <livewire:wish-list />
    </section>
@endsection