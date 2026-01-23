<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Left side - Form -->
        <div class="flex w-full flex-col bg-white lg:w-1/2">
            <!-- Logo header -->
            <div class="flex items-center justify-between px-6 py-6 lg:px-12">
                <a href="{{ route('home') }}">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10">
                </a>
                <x-language-switcher />
            </div>
            <!-- Main content area - centered -->
            <div class="flex flex-1 flex-col items-center justify-center px-6 pb-6 lg:px-12">
                <div class="w-full max-w-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Right side - Illustration with floating elements -->
        <div class="hidden bg-amber-600 lg:flex lg:w-1/2 lg:items-center lg:justify-center relative overflow-hidden">
            <!-- Concentric circles -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[800px] h-[800px] border-2 border-white/20 rounded-full"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[600px] h-[600px] border-2 border-white/25 rounded-full"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[400px] h-[400px] border-2 border-white/20 rounded-full"></div>
            </div>

            <div class="relative w-full max-w-2xl px-12">
                <!-- Dashboard mockup card -->
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-6 relative z-10">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Beszerzés Dashboard</div>
                                <div class="text-xs text-gray-500">Logistics Overview</div>
                            </div>
                        </div>
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                    </div>

                    <!-- Stats row -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">Pending Orders</div>
                            <div class="text-lg font-bold text-gray-900">24</div>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-3">
                            <div class="text-xs text-amber-600 mb-1">In Transit</div>
                            <div class="text-lg font-bold text-amber-700">12</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3">
                            <div class="text-xs text-green-600 mb-1">Delivered</div>
                            <div class="text-lg font-bold text-green-700">156</div>
                        </div>
                    </div>

                    <!-- List items -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center text-xs font-medium text-amber-700">AB</div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">Office Supplies</div>
                                    <div class="text-xs text-gray-500">Supplier: ABC Corp</div>
                                </div>
                            </div>
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">Processing</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-xs font-medium text-green-700">XY</div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">IT Equipment</div>
                                    <div class="text-xs text-gray-500">Supplier: XYZ Ltd</div>
                                </div>
                            </div>
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Delivered</span>
                        </div>
                    </div>
                </div>

                <!-- Floating notification -->
                <div class="absolute -left-8 top-1/4 bg-white rounded-lg shadow-lg p-3 border border-gray-100 animate-float z-20">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-900">Order Approved</div>
                            <div class="text-xs text-gray-500">Just now</div>
                        </div>
                    </div>
                </div>

                <!-- Floating alert -->
                <div class="absolute -right-4 bottom-1/4 bg-white rounded-lg shadow-lg p-3 border border-gray-100 animate-float-delayed z-20">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-900">Low Stock Alert</div>
                            <div class="text-xs text-gray-500">5 items</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-16 right-16 w-24 h-24 border-2 border-white/30 rounded-full"></div>
            <div class="absolute bottom-24 left-12 w-16 h-16 bg-amber-400/40 rounded-full"></div>
        </div>
    </div>

    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
