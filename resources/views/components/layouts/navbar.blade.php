<nav class="bg-white border-b border-gray-100 fixed w-full top-0 z-50" x-data="{ mobileMenuOpen: false, openDropdown: null }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            {{-- Left: Logo --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}"
                        class="h-10">
                    <span class="text-sm font-semibold text-amber-600">Beszerzés</span>
                </a>
            </div>

            {{-- Center: Navigation Links --}}
            <div class="hidden lg:flex items-center gap-1">
                {{-- Features --}}
                <a href="#funkciok"
                    class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Funkciók
                </a>

                {{-- Integrations --}}
                <a href="#integraciok"
                    class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Integrációk
                </a>

                {{-- Pricing --}}
                <a href="#arak"
                    class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Árak
                </a>

                {{-- FAQ --}}
                <a href="#gyik"
                    class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    GYIK
                </a>
            </div>

            {{-- Right: Actions --}}
            <div class="hidden lg:flex items-center gap-4">
                {{-- Language Switcher --}}
                <x-language-switcher />

                @guest
                    {{-- Log in --}}
                    <a href="/admin/login" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        Bejelentkezés
                    </a>

                    {{-- Get Started (filled) --}}
                    <a href="/admin/register"
                        class="inline-flex items-center gap-1 px-5 py-2 text-sm font-medium text-white bg-amber-600 rounded-full hover:bg-amber-700 transition-colors">
                        Ingyenes próba
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @endguest

                @auth
                    {{-- Dashboard link --}}
                    <a href="{{ route('filament.admin.pages.dashboard') }}"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        Dashboard
                    </a>

                    {{-- User dropdown --}}
                    <div class="relative" @mouseenter="openDropdown = 'user'" @mouseleave="openDropdown = null">
                        <button
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                            <div
                                class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold text-sm">
                                {{ substr(auth()->user()->name ?? auth()->user()->email, 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openDropdown === 'user'" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="{{ route('filament.admin.pages.dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                            <hr class="my-1 border-gray-200">
                            <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Kijelentkezés
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

            </div>

            {{-- Mobile menu button --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 text-gray-400 hover:text-gray-600 transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="mobileMenuOpen" x-collapse class="lg:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-4 space-y-3">
            <a href="#funkciok" class="block py-2 text-sm font-medium text-gray-700"
                @click="mobileMenuOpen = false">Funkciók</a>
            <a href="#integraciok" class="block py-2 text-sm font-medium text-gray-700"
                @click="mobileMenuOpen = false">Integrációk</a>
            <a href="#arak" class="block py-2 text-sm font-medium text-gray-700"
                @click="mobileMenuOpen = false">Árak</a>
            <a href="#gyik" class="block py-2 text-sm font-medium text-gray-700"
                @click="mobileMenuOpen = false">GYIK</a>

            {{-- Language Switcher for Mobile --}}
            <div class="py-2">
                <x-language-switcher />
            </div>

            <hr class="border-gray-200">

            @guest
                <a href="/admin/login" class="block py-2 text-sm font-medium text-gray-700">Bejelentkezés</a>
                <a href="/admin/register"
                    class="block w-full text-center py-2.5 text-sm font-medium text-white bg-amber-600 rounded-full">
                    Ingyenes próba
                </a>
            @endguest

            @auth
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="block py-2 text-sm font-medium text-gray-700">Dashboard</a>
                <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-sm font-medium text-red-600">
                        Kijelentkezés
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>
