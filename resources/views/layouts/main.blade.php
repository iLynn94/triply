<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireStyles
   <title>
        @hasSection('title')
            @yield('title') | Triply
        @else
            Triply
        @endif
    </title>
</head>

<body class="font-inter bg-gray-100">
    <x-ui.toast />

    @include('layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    @livewireScripts

    {{-- Flash messages as toast notifications --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    type: 'success',
                    content: '{{ session('success') }}'
                }
            }));
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    type: 'error',
                    content: '{{ session('error') }}'
                }
            }));
        });
    </script>
    @endif
</body>
</html>

