<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Warehouse Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-lg dark:border-gray-800 dark:bg-gray-900/80">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10">
                        <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                    </a>
                    <div class="hidden gap-6 md:flex">
                        <a href="#features" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Features</a>
                        <a href="#modules" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Modules</a>
                        <a href="#about" class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">About</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Admin Panel
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-white to-gray-50 py-20 dark:from-gray-900 dark:to-gray-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl dark:text-white">
                    Complete Warehouse Management Solution
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600 dark:text-gray-400">
                    Streamline your inventory, orders, and warehouse operations with our comprehensive management system. Track stock, manage suppliers, and optimize your supply chain.
                </p>
                <div class="mt-10 flex items-center justify-center gap-6">
                    <a href="{{ route('filament.admin.auth.login') }}" class="rounded-lg bg-blue-600 px-8 py-3 text-base font-semibold text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Get Started
                    </a>
                    <a href="#features" class="rounded-lg border border-gray-300 bg-white px-8 py-3 text-base font-semibold text-gray-900 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-white py-20 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                    Powerful Features
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600 dark:text-gray-400">
                    Everything you need to manage your warehouse efficiently
                </p>
            </div>

            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-blue-600 dark:bg-blue-500">
                        <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Inventory Management</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Real-time stock tracking across multiple warehouses. Monitor inventory levels, track batches, and manage product valuations.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-green-600 dark:bg-green-500">
                        <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Order Management</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Streamline order processing from creation to fulfillment. Track receipts, deliveries, and returns with ease.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-purple-600 dark:bg-purple-500">
                        <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Partner Management</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Manage relationships with customers, suppliers, and employees in one centralized system.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-orange-600 dark:bg-orange-500">
                        <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Intrastat Declarations</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Automated generation of Intrastat declarations for inbound and outbound EU trade with XML export.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-red-600 dark:bg-red-500">
                        <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Advanced Reporting</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Comprehensive reports including inventory valuation, warehouse stock overview, and expected arrivals.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                    <div class="flex size-12 items-center justify-center rounded-lg bg-teal-600 dark:bg-teal-500">
                        <svg class="size-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-white">Multi-Warehouse Support</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Manage multiple warehouse locations with stock tracking, transfers, and location-specific operations.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section id="modules" class="bg-gray-50 py-20 dark:bg-gray-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                    Core Modules
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600 dark:text-gray-400">
                    Integrated modules for complete warehouse operations
                </p>
            </div>

            <div class="mt-16 grid gap-6 lg:grid-cols-2">
                <!-- Module List -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Products & Categories</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Comprehensive product catalog with category management and CN code integration</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Stock & Batch Tracking</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Real-time stock levels, batch management, and automated reordering</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Orders & Receipts</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Order processing, receipt management, and delivery tracking</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Return Management</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Streamlined return deliveries with multi-step workflows</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Warehouse Management</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Multiple warehouses with stock overview and location tracking</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Customer & Supplier Portal</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage business relationships with detailed partner profiles</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Inventory Valuation</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Comprehensive inventory reports with financial valuation</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            <svg class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">User Management</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Role-based access control and employee management</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-white py-20 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                        Built for Modern Warehouses
                    </h2>
                    <p class="mt-6 text-lg text-gray-600 dark:text-gray-400">
                        Our warehouse management system is designed to handle the complexities of modern supply chain operations. From inventory tracking to compliance reporting, we provide the tools you need to succeed.
                    </p>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        Built with Laravel and Filament, our system offers a robust, scalable solution that grows with your business.
                    </p>
                    <div class="mt-8 flex items-center gap-4">
                        <a href="{{ route('filament.admin.auth.login') }}" class="rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Access Admin Panel
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                        <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">100%</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Real-time Tracking</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                        <div class="text-4xl font-bold text-green-600 dark:text-green-400">Multi</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Warehouse Support</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                        <div class="text-4xl font-bold text-purple-600 dark:text-purple-400">EU</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Intrastat Compliant</div>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-800 dark:bg-gray-950">
                        <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">24/7</div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">Accessibility</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-6">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="#features" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Features</a>
                    <a href="#modules" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Modules</a>
                    <a href="{{ route('filament.admin.auth.login') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Admin</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
