<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Triply</title>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-gray-100 flex">
    <div class="flex w-full min-h-screen flex-col md:flex-row">

        {{-- Left Image Section --}}
        <aside class="relative hidden md:block w-1/2">
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('/images/auth-bg.jpg')"></div>
            <div class="absolute inset-0 bg-black/40"></div>

            {{-- Logo for Large Screens --}}
            <div class="absolute top-8 left-8 z-10">
                <a href="/" class="flex items-center gap-2 text-2xl font-bold text-white no-underline md:text-[1.75rem]">
                    <img src="/images/logo.png" alt="Triply Logo" class="h-8 w-auto">
                    <span>Triply</span>
                </a>
            </div>
        </aside>

        {{-- Mobile BG --}}
        <div class="md:hidden w-full h-48 bg-cover bg-center"
             style="background-image: url('/images/auth-bg.jpg')">
            <div class="w-full h-full bg-black/20"></div>
        </div>

        {{-- Right Form Section --}}
        <main class="flex-1 flex items-center justify-center bg-white px-6 py-12 md:px-16">
            <div class="w-full max-w-md">
                {{-- Logo for Small Screens --}}
                <div class="mb-12 md:hidden">
                    <a href="/" class="flex items-center gap-2 text-3xl font-bold text-orange-700 no-underline">
                        <img src="/images/logo.png" alt="Triply Logo" class="h-8 w-auto">
                        <span>Triply</span>
                    </a>
                </div>

                <h1 class="text-3xl font-bold mb-6 text-gray-800">
                    @yield('title')
                </h1>

                @yield('content')
            </div>
        </main>

    </div>

    <x-ui.toast />

    @if(session('redirect_to'))
        <script>
            // Wait 2 seconds then redirect to the intended page
            setTimeout(function() {
                window.location.href = @js(session('redirect_to'));
            }, 2000);
        </script>
    @endif
</body>
</html>
