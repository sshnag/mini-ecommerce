<nav x-data="{ open: false, mobileMenuOpen: false }" class="luxury-nav bg-white shadow-md fixed w-full z-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-2xl font-serif text-gold">TIFFANY</a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-black hover:text-gold transition-colors duration-300">Collections</a>
                <a href="#" class="text-black hover:text-gold transition-colors duration-300">Rings</a>
                <a href="#" class="text-black hover:text-gold transition-colors duration-300">Bracelets</a>

                <!-- Cart Link -->
                <a href="{{ route('cart.index') }}" class="text-black hover:text-gold transition-colors duration-300 relative">
                    Bag (<span class="cart-count">0</span>)
                </a>

                <!-- Authentication Links -->
                @if (Route::has('login'))
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="text-black hover:text-gold transition-colors duration-300">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-black hover:text-gold transition-colors duration-300">Register</a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gold hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="md:hidden" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition:enter="transition ease-out duration-100 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Collections</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Rings</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Bracelets</a>
            <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Bag (0)</a>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Logout</a>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-black hover:text-gold hover:bg-gray-50">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>
