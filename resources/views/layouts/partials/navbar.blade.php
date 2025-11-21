<nav class="sticky top-0 z-50 flex h-auto items-center justify-between bg-white px-4 py-2.5 shadow-md transition-all duration-300 ease-in-out md:px-8 lg:px-32 xl:px-48">
    
    {{-- Logo Section --}}
    <div class="flex items-center">
        <a href="/" class="flex items-center gap-2 text-2xl font-bold text-orange-600 no-underline md:text-[1.75rem]">
            <img src="/images/logo.png" alt="Triply Logo" class="h-8 w-auto">
            <span>Triply</span>
        </a>
    </div>

    {{-- Desktop Navigation Links --}}
    <div class="hidden md:flex">
        <ul class="flex list-none gap-12 p-0">
            <li>
                <a href="/" class="text-xl font-medium text-gray-600 transition-all duration-300 ease-in-out hover:text-gray-900 {{ request()->is('/') ? 'font-semibold text-gray-900' : '' }}">
                    Home
                </a>
            </li>

            @auth
                <li>
                    <a href="/dashboard" class="text-xl font-medium text-gray-600 transition-all duration-300 ease-in-out hover:text-gray-900 {{ request()->is('dashboard') ? 'font-semibold text-gray-900' : '' }}">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="/create-travel-package" class="text-xl font-medium text-gray-600 transition-all duration-300 ease-in-out hover:text-gray-900 {{ request()->is('create-travel-package') ? 'font-semibold text-gray-900' : '' }}">
                        Create Travel Package
                    </a>
                </li>
            @else
                <li>
                    <a href="/sign-up" class="text-xl font-medium text-gray-600 transition-all duration-300 ease-in-out hover:text-gray-900">
                        Sign Up
                    </a>
                </li>

                <li>
                    <a href="/sign-in" class="text-xl font-medium text-gray-600 transition-all duration-300 ease-in-out hover:text-gray-900">
                        Sign In
                    </a>
                </li>
            @endauth
        </ul>
    </div>

    @auth
        {{-- Desktop Right Icons (User Dropdown) --}}
        <div class="hidden md:flex">
            <ul class="flex list-none gap-7 p-0">
                <li>
                    <a href="/cart" class="cursor-pointer border-none bg-transparent p-0">
                        <x-dynamic-component
                            :component="request()->is('cart') ? 'heroicon-s-shopping-bag' : 'heroicon-o-shopping-bag'"
                            class="h-[25px] w-[25px] text-gray-700"
                        />
                    </a>
                </li>

                <li>
                    <a href="/wishlist" class="cursor-pointer border-none bg-transparent p-0">
                        <x-dynamic-component
                            :component="request()->is('wishlist') ? 'heroicon-s-heart' : 'heroicon-o-heart'"
                            class="h-[25px] w-[25px] text-gray-700"
                        />
                    </a>
                </li>

                <li class="group relative">
                    <button class="cursor-pointer border-none bg-transparent p-0">
                        <x-dynamic-component
                            :component="request()->is('profile') ? 'heroicon-s-user' : 'heroicon-o-user'"
                            class="h-[25px] w-[25px] text-gray-700"
                        />
                    </button>

                    {{-- Dropdown Menu --}}
                    <div class="absolute right-0 mt-2 hidden w-40 rounded-lg border border-gray-300 bg-white shadow-md before:absolute before:-top-2.5 before:left-0 before:h-2.5 before:w-full before:content-[''] group-hover:block">
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="block w-full rounded-lg px-3.5 py-2 text-left text-base font-medium text-gray-800 no-underline transition-colors hover:bg-gray-200">
                                Log Out
                            </button>
                        </form>
                        <hr class="mx-auto my-0 w-[85%] text-gray-400">
                        <a href="/bookings" class="block rounded-lg px-3.5 py-2 text-base font-medium text-gray-800 no-underline transition-colors hover:bg-gray-200 {{ request()->is('bookings') ? 'font-semibold text-gray-900' : 'text-gray-600' }}">
                            Bookings
                        </a>
                        <hr class="mx-auto my-0 w-[85%] text-gray-400">
                        <a href="/profile" class="block rounded-lg px-3.5 py-2 text-base font-medium text-gray-800 no-underline transition-colors hover:bg-gray-200 {{ request()->is('profile') ? 'font-semibold text-gray-900' : 'text-gray-600' }}">
                            Edit Profile
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    @else
        {{-- Invisible spacer to maintain layout balance --}}
        <div class="hidden md:flex">
            <div class="flex gap-7">
                <div class="h-[25px] w-[25px]"></div>
                <div class="h-[25px] w-[25px]"></div>
                <div class="h-[25px] w-[25px]"></div>
            </div>
        </div>
    @endauth

    {{-- Mobile Menu Toggle Button --}}
    <button 
        id="mobileMenuToggle"
        class="block cursor-pointer border-none bg-transparent p-2 md:hidden"
        aria-label="Toggle mobile menu"
    >
        <span id="menuBar1" class="block h-[3px] w-[25px] bg-gray-900 transition-all duration-300"></span>
        <span id="menuBar2" class="mt-[5px] block h-[3px] w-[25px] bg-gray-900 transition-all duration-300"></span>
        <span id="menuBar3" class="mt-[5px] block h-[3px] w-[25px] bg-gray-900 transition-all duration-300"></span>
    </button>
</nav>

{{-- Mobile Menu Overlay --}}
<div id="mobileMenu" class="fixed left-0 top-0 z-2000 flex h-full w-full -translate-x-full flex-col overflow-y-auto bg-white p-4 shadow-lg transition-transform duration-300 ease-in-out md:hidden">
    
    {{-- Mobile Header --}}
    <div class="relative flex w-full items-center justify-between border-b border-gray-400 pb-4">
        <a href="/" class="flex items-center gap-2 text-[1.5rem] font-bold text-orange-600 no-underline">
            <img src="/images/logo.png" alt="Triply Logo" class="h-10 w-auto">
            <span>Triply</span>
        </a>
        <button 
            id="mobileMenuClose"
            class="absolute right-0 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border-none bg-transparent text-[2rem] text-gray-900 transition-colors hover:bg-gray-100"
            aria-label="Close mobile menu"
        >
             <x-heroicon-o-x-mark class="h-[30px] w-[30px] text-gray-700" />
        </button>
    </div>

    {{-- Mobile Navigation Links --}}
    <ul class="my-6 list-none p-0">
        
        <li class="mb-5">
            <a href="/" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('/') ? 'font-semibold text-gray-900' : '' }}">
                Home
            </a>
        </li>
        
        @auth

            <li class="mb-5">
                <a href="/dashboard" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('dashboard') ? 'font-semibold text-gray-900' : '' }}">
                    Dashboard
                </a>
            </li>

            <li class="mb-5">
                <a href="/create-travel-package" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('create-travel-package') ? 'font-semibold text-gray-900' : '' }}">
                    Create Travel Package
                </a>
            </li>

            <hr class="my-6 h-px border-0 bg-gray-400 opacity-50">

            <li class="mb-5">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 text-left text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600">
                        Log Out
                    </button>
                </form>
            </li>

            <li class="mb-5">
                <a href="/cart" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('cart') ? 'font-semibold text-gray-900' : '' }}">
                    Cart
                </a>
            </li>

            <li class="mb-5">
                <a href="/wishlist" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('wishlist') ? 'font-semibold text-gray-900' : '' }}">
                    Wishlist
                </a>
            </li>

            <li class="mb-5">
                <a href="/bookings" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('bookings') ? 'font-semibold text-gray-900' : '' }}">
                    Bookings
                </a>
            </li>

            <hr class="my-6 h-px border-0 bg-gray-400 opacity-50">

            <li>
               <a href="/profile" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600 {{ request()->is('profile') ? 'font-semibold text-gray-900' : '' }}">
                    Edit Profile
                </a>
            </li>
        @else
            <li class="mb-5">
                <a href="/sign-up" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600">
                    Sign Up
                </a>
            </li>
            <li class="mb-5">
                <a href="/sign-in" class="flex items-center py-2 text-[1.15rem] text-gray-600 transition-colors hover:text-orange-600">
                    Sign In
                </a>
            </li>
        @endauth
    </ul>
</div>

{{-- Mobile Menu JavaScript --}}
<script>
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const menuBar1 = document.getElementById('menuBar1');
    const menuBar2 = document.getElementById('menuBar2');
    const menuBar3 = document.getElementById('menuBar3');

    mobileMenuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('-translate-x-full');
        const isOpen = !mobileMenu.classList.contains('-translate-x-full');
        
        if (isOpen) {
            document.body.classList.add('overflow-hidden');
            menuBar1.classList.add('translate-y-2', 'rotate-45');
            menuBar2.classList.add('opacity-0');
            menuBar3.classList.add('-translate-y-2.5', '-rotate-45');
        } else {
            document.body.classList.remove('overflow-hidden');
            menuBar1.classList.remove('translate-y-2', 'rotate-45');
            menuBar2.classList.remove('opacity-0');
            menuBar3.classList.remove('-translate-y-2.5', '-rotate-45');
        }
    });

    mobileMenuClose.addEventListener('click', () => {
        mobileMenu.classList.add('-translate-x-full');
        document.body.classList.remove('overflow-hidden');
        menuBar1.classList.remove('translate-y-2', 'rotate-45');
        menuBar2.classList.remove('opacity-0');
        menuBar3.classList.remove('-translate-y-2.5', '-rotate-45');
    });
</script>