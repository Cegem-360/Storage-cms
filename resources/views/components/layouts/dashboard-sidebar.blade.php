{{-- Dashboard sidebar - 240px width --}}
<aside
    class="fixed inset-y-0 left-0 z-50 w-60 bg-[#292F4C] text-white flex flex-col transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
    :class="{
        'lg:-translate-x-full': !sidebarOpen,
        'lg:translate-x-0': sidebarOpen,
        '-translate-x-full': !mobileMenuOpen,
        'translate-x-0': mobileMenuOpen
    }"
>
    {{-- Logo area --}}
    <div class="h-16 flex items-center px-4 border-b border-white/10">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8 brightness-0 invert">
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6">
        {{-- Main navigation --}}
        <div>
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Navigation') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.*') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Inventory management --}}
        <div>
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Inventory') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard.products') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.products') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        {{ __('Products') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.categories') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.categories') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ __('Categories') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.warehouses') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.warehouses') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ __('Warehouses') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.stocks') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.stocks') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        {{ __('Stock Levels') }}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Operations --}}
        <div>
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Operations') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard.orders') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.orders') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('Purchase Orders') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.receipts') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.receipts') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Receipts') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.movements') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.movements') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        {{ __('Stock Movements') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.inventories') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.inventories') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        {{ __('Inventory Counts') }}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Partners --}}
        <div>
            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Partners') }}</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard.suppliers') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.suppliers') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                        {{ __('Suppliers') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.customers') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                            {{ request()->routeIs('dashboard.customers') ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        {{ __('Customers') }}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Filament Admin --}}
        @auth
            <div>
                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Administration') }}</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ __('Admin Panel') }}
                            <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        @endauth
    </nav>

    {{-- User section at bottom --}}
    @auth
        <div class="border-t border-white/10 p-4">
            <a href="{{ route('dashboard.profile') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition">
                <div class="w-8 h-8 rounded-full bg-linear-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="truncate font-medium">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-gray-400">{{ auth()->user()->email }}</p>
                </div>
            </a>
        </div>
    @endauth
</aside>
