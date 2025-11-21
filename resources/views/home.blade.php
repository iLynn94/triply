@extends('layouts.main')

@section('content')
    <section
        id="heroSection"
        class="relative flex min-h-[90vh] sm:min-h-screen items-center justify-start overflow-hidden px-[4vw] text-white"
    >
        {{-- Background Image Layers for Smooth Transition --}}
        <div id="bgLayer1" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('/images/stock-8.jpg');"></div>
        <div id="bgLayer2" class="absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-1000" style="background-image: url('/images/stock-9.jpg');"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-[90%] pb-[4vw] pl-5 xl:pl-18">
            <div class="flex flex-col items-start gap-2 md:flex-row md:flex-wrap md:items-baseline md:gap-4">
                <h2 class="text-[clamp(2.75rem,4.5vw,6rem)] font-bold leading-tight text-white/80">
                    Explore
                </h2>
                <h2 class="text-[clamp(4.5rem,8vw,10rem)] font-semibold leading-tight text-white/90">
                    <x-ui.flip-words
                        :words="['Maldives', 'Mombasa', 'Serengeti', 'Dubai', 'Bali', 'Lamu', 'Zanzibar']"
                        :duration="3000"
                        class="text-[clamp(4.5rem,8vw,10rem)] font-semibold text-white/90"
                    />
                </h2>
            </div>
        </div>
    </section>

    {{-- Background Slideshow Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bgLayer1 = document.getElementById('bgLayer1');
            const bgLayer2 = document.getElementById('bgLayer2');
            const images = [
                '/images/stock-8.jpg',
                '/images/stock-9.jpg',
                '/images/stock-10.jpg'
            ];

            let currentIndex = 0;
            let activeLayer = bgLayer1;
            let inactiveLayer = bgLayer2;

            function changeBackground() {
                currentIndex = (currentIndex + 1) % images.length;
                const nextImage = images[currentIndex];

                // Set the next image on the inactive layer
                inactiveLayer.style.backgroundImage = `url('${nextImage}')`;

                // Use GSAP to crossfade
                gsap.to(activeLayer, {
                    opacity: 0,
                    duration: 1.5,
                    ease: 'power2.inOut'
                });

                gsap.to(inactiveLayer, {
                    opacity: 1,
                    duration: 1.5,
                    ease: 'power2.inOut'
                });

                // Swap active and inactive layers
                [activeLayer, inactiveLayer] = [inactiveLayer, activeLayer];
            }

            // Change background every 55 seconds
            setInterval(changeBackground, 55000);
        });
    </script>

    <section class="container mx-auto px-4 py-8 md:px-6 lg:px-12 xl:px-16">
        <livewire:trips-list />
    </section>
@endsection
