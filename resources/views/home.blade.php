<x-layouts.app>
    {{-- Navbar --}}
    <x-layouts.navbar />

    {{-- ==================== --}}
    {{-- 1. HERO SECTION --}}
    {{-- ==================== --}}
    <section class="bg-linear-to-b from-amber-50 to-white pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    {{ __('New: Mobile barcode receiving') }}
                </div>

                {{-- H1 --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-gray-900 mb-6 font-heading leading-tight">
                    {{ __('Warehouse inventory and shipments in one transparent system') }}
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    {{ __('Always know what\'s in stock, what\'s in transit, and when to reorder. End the Excel chaos and stockouts.') }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <a href="https://cegem360.eu/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-amber-600 rounded-full hover:bg-amber-700 transition-colors shadow-lg">
                        {{ __('Get started') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="https://cegem360.eu/kapcsolat" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-amber-700 bg-white border-2 border-amber-200 rounded-full hover:bg-amber-50 transition-colors">
                        {{ __('Request a demo') }}
                    </a>
                    <a href="/login" class="inline-flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-amber-600 transition-colors">
                        {{ __('Log in to the app') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Hero Image/Dashboard Preview --}}
            <div class="mt-16 relative">
                {{-- Background decorations --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-amber-200 rounded-full opacity-20 blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-60 h-60 bg-orange-200 rounded-full opacity-20 blur-3xl"></div>
                </div>

                <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden max-w-5xl mx-auto">
                    {{-- Header bar --}}
                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-3">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 flex items-center justify-center">
                            <div class="bg-white border border-gray-200 rounded-lg px-4 py-1.5 flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                beszerzes.cegem360.eu/dashboard
                            </div>
                        </div>
                    </div>

                    {{-- Dashboard canvas --}}
                    <div class="relative bg-linear-to-br from-gray-50 via-amber-50/20 to-gray-50 p-6 lg:p-8">
                        {{-- Grid pattern --}}
                        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #6b7280 1px, transparent 1px); background-size: 24px 24px;"></div>

                        <div class="relative" style="z-index: 2;">
                            {{-- Stat boxes --}}
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 animate-float">
                                    <div class="text-2xl font-bold text-gray-900 mb-1">1,247</div>
                                    <div class="text-sm text-gray-500">{{ __('Total SKUs') }}</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-amber-200 animate-float-delayed">
                                    <div class="text-2xl font-bold text-amber-600 mb-1">12</div>
                                    <div class="text-sm text-amber-600">{{ __('Low stock') }}</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 animate-float-delayed-2">
                                    <div class="text-2xl font-bold text-blue-600 mb-1">8</div>
                                    <div class="text-sm text-gray-500">{{ __('In transit') }}</div>
                                </div>
                            </div>

                            {{-- Stock list --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <span class="font-semibold text-gray-900">{{ __('Stock Overview') }}</span>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <div class="px-4 py-3 flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ __('M8 bolt 50mm') }}</div>
                                            <div class="text-sm text-gray-500">SKU-2847</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">{{ __('2,450 pcs') }}</div>
                                            <div class="text-sm text-emerald-600 flex items-center gap-1 justify-end">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                                {{ __('OK') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 flex items-center justify-between bg-amber-50/50">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ __('Ball bearing 6205') }}</div>
                                            <div class="text-sm text-gray-500">SKU-1093</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">{{ __('45 pcs') }}</div>
                                            <div class="text-sm text-amber-600 flex items-center gap-1 justify-end">
                                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                                {{ __('Low') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 flex items-center justify-between bg-red-50/50">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ __('Steel sheet 2mm') }}</div>
                                            <div class="text-sm text-gray-500">SKU-0521</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">{{ __('8 sheets') }}</div>
                                            <div class="text-sm text-red-600 flex items-center gap-1 justify-end">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                {{ __('Critical') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating status cards --}}
                        <div class="absolute bottom-4 left-4 right-4 flex flex-wrap justify-center gap-3">
                            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/90 backdrop-blur border border-emerald-200 rounded-full text-sm shadow-md">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-gray-700 font-medium">{{ __('Shipment arrived!') }}</span>
                                <span class="text-gray-500">TRANS-2024-0892</span>
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/90 backdrop-blur border border-amber-200 rounded-full text-sm shadow-md">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span class="text-gray-700 font-medium">{{ __('Order suggestion') }}</span>
                                <span class="text-amber-600">{{ __('3 products below minimum') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 2. PROBLEM-SOLUTION SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Do you recognize these problems?') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Most industrial companies struggle with inventory and procurement transparency.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <x-landing.problem-card color="red" :title="__('Excel chaos')" :description="__('Multiple spreadsheets, multiple versions - nobody knows which inventory list is current.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </x-slot:icon>
                </x-landing.problem-card>

                <x-landing.problem-card color="amber" :title="__('Stockouts')" :description="__('You always find out something ran out when you urgently need it.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </x-slot:icon>
                </x-landing.problem-card>

                <x-landing.problem-card color="orange" :title="__('Lost orders')" :description="__('It was discussed with the supplier, but nobody knows who handled it or the status.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-slot:icon>
                </x-landing.problem-card>

                <x-landing.problem-card color="purple" :title="__('Manual inventory counting')" :description="__('Hours spent counting stock, and the numbers still don\'t add up.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </x-slot:icon>
                </x-landing.problem-card>
            </div>

            {{-- Solution --}}
            <div class="bg-amber-50 rounded-2xl p-8 lg:p-12 border border-amber-100 max-w-5xl mx-auto">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <div class="flex-1">
                        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4 font-heading">{{ __('Cégem360 Procurement solves all of this') }}</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            {{ __('Finally one place where you see all inventory, suppliers and shipments - in real time, on any device.') }}
                        </p>
                        <ul class="space-y-3">
                            <x-landing.check-item icon-class="w-5 h-5" class="text-gray-700">{{ __('Real-time inventory tracking') }}</x-landing.check-item>
                            <x-landing.check-item icon-class="w-5 h-5" class="text-gray-700">{{ __('Automatic stock alerts') }}</x-landing.check-item>
                            <x-landing.check-item icon-class="w-5 h-5" class="text-gray-700">{{ __('Supplier management and evaluation') }}</x-landing.check-item>
                        </ul>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-60%</div>
                            <div class="text-sm text-gray-600">{{ __('Stockout reduction') }}</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-70%</div>
                            <div class="text-sm text-gray-600">{{ __('Inventory counting time') }}</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-40%</div>
                            <div class="text-sm text-gray-600">{{ __('Procurement cycle') }}</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-25%</div>
                            <div class="text-sm text-gray-600">{{ __('Overstocking') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 3. FEATURES SECTION --}}
    {{-- ==================== --}}
    <section id="funkciok" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('All the tools for efficient inventory management') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('The Cégem360 Procurement-logistics module helps manage warehouses and suppliers in 6 key areas.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-landing.feature-card :title="__('Inventory Management')" :description="__('Real-time inventory tracking across multiple warehouses with automatic alerts.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </x-slot:icon>
                    <x-landing.check-item>{{ __('Multi-warehouse and location support') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Minimum stock alerts') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Serial number and expiry date tracking') }}</x-landing.check-item>
                </x-landing.feature-card>

                <x-landing.feature-card :title="__('Procurement')" :description="__('Supplier management, quotation requests and automatic reorder suggestions.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </x-slot:icon>
                    <x-landing.check-item>{{ __('Supplier database and rating') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Quote management and comparison') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Order approval workflow') }}</x-landing.check-item>
                </x-landing.feature-card>

                <x-landing.feature-card :title="__('Shipment tracking')" :description="__('Inbound and outbound shipment status in one place, in real time.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </x-slot:icon>
                    <x-landing.check-item>{{ __('Expected arrival times') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Shipment alerts for delays') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Carrier integration') }}</x-landing.check-item>
                </x-landing.feature-card>

                <x-landing.feature-card :title="__('Warehouse operations')" :description="__('Receiving, issuing and inventory counting - even on mobile, with barcodes.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </x-slot:icon>
                    <x-landing.check-item>{{ __('Receiving with barcode or manually') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Issuing and transfers') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Warehouse location management (shelf, row)') }}</x-landing.check-item>
                </x-landing.feature-card>

                <x-landing.feature-card :title="__('Reports and analytics')" :description="__('ABC analysis, turnover metrics and inventory value tracking.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </x-slot:icon>
                    <x-landing.check-item>{{ __('Inventory turnover metrics') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('ABC analysis') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Supplier performance report') }}</x-landing.check-item>
                </x-landing.feature-card>

                <x-landing.feature-card :title="__('Integrations')" :description="__('Webshop sync, ERP connection and barcode scanner support.')">
                    <x-slot:icon>
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                    </x-slot:icon>
                    <x-landing.check-item>{{ __('WooCommerce, Shopify integration') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('Barcode scanner support') }}</x-landing.check-item>
                    <x-landing.check-item>{{ __('EDI supplier connection') }}</x-landing.check-item>
                </x-landing.feature-card>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 4. WORKFLOW SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Automatic reordering in 7 steps') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('The system monitors stock levels and automatically suggests orders - you just approve.') }}
                </p>
            </div>

            {{-- Workflow Steps --}}
            <div class="relative max-w-4xl mx-auto">
                {{-- Connecting line --}}
                <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-amber-200 -translate-x-1/2"></div>

                <div class="space-y-8">
                    <x-landing.workflow-step side="left" :title="__('Stock decreases')" :description="__('The system detects stock below minimum level')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>

                    <x-landing.workflow-step side="right" :title="__('Alert')" :description="__('Automatic notification to the buyer')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>

                    <x-landing.workflow-step side="left" :title="__('Suggestion')" :description="__('Order suggestion with the preferred supplier')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>

                    <x-landing.workflow-step side="right" :title="__('Approval')" :description="__('One click or automatic processing')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>

                    <x-landing.workflow-step side="left" :title="__('Order placement')" :description="__('Email or EDI to the supplier')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>

                    <x-landing.workflow-step side="right" :title="__('Tracking')" :description="__('Shipment status on the dashboard')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>

                    <x-landing.workflow-step side="left" color="emerald" :title="__('Receiving')" :description="__('With barcode or manually, stock updates')">
                        <x-slot:icon>
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </x-slot:icon>
                    </x-landing.workflow-step>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 5. INTEGRATIONS SECTION --}}
    {{-- ==================== --}}
    <section id="integraciok" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Connected to the entire ecosystem') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('The Procurement module works with webshops, ERP systems and other Cégem360 modules.') }}
                </p>
            </div>

            {{-- Integration Hub --}}
            <div class="relative max-w-4xl mx-auto">
                <div class="flex flex-wrap justify-center items-center gap-4">
                    <x-landing.integration-card color="purple" name="WooCommerce">
                        <x-slot:icon>
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </x-slot:icon>
                    </x-landing.integration-card>

                    <x-landing.integration-card color="green" name="Shopify">
                        <x-slot:icon>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </x-slot:icon>
                    </x-landing.integration-card>

                    <x-landing.integration-card color="indigo" :name="__('Manufacturing')">
                        <x-slot:icon>
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </x-slot:icon>
                    </x-landing.integration-card>
                </div>

                {{-- Center - Beszerzés --}}
                <div class="flex justify-center my-6">
                    <div class="bg-linear-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-center shadow-xl">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span class="text-white font-semibold">{{ __('Procurement') }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center items-center gap-4">
                    <x-landing.integration-card color="red" :name="__('Sales')">
                        <x-slot:icon>
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </x-slot:icon>
                    </x-landing.integration-card>

                    <x-landing.integration-card color="emerald" :name="__('Controlling')">
                        <x-slot:icon>
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </x-slot:icon>
                    </x-landing.integration-card>

                    <x-landing.integration-card color="gray" :name="__('Barcode')">
                        <x-slot:icon>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </x-slot:icon>
                    </x-landing.integration-card>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 6. RESULTS SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-white mb-4 font-heading">
                    {{ __('What our clients achieved with the Procurement module') }}
                </h2>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    {{ __('Real numbers from real clients. Average ROI is 2-4 months.') }}
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-60%</div>
                    <div class="text-sm text-gray-400">{{ __('Number of stockouts') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-25%</div>
                    <div class="text-sm text-gray-400">{{ __('Overstocking') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-70%</div>
                    <div class="text-sm text-gray-400">{{ __('Inventory counting time') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-40%</div>
                    <div class="text-sm text-gray-400">{{ __('Procurement cycle') }}</div>
                </div>
                <div class="text-center col-span-2 lg:col-span-1">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-50%</div>
                    <div class="text-sm text-gray-400">{{ __('Admin work') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 7. TESTIMONIALS SECTION --}}
    {{-- ==================== --}}
    @if(false)
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('What do our clients say?') }}
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Testimonial 1 --}}
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6">
                        {{ __('Tracking inventory in Excel used to be a nightmare. Now one click and I see what\'s missing. Since the automatic alerts, there hasn\'t been a single stockout-related shutdown.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-semibold">
                            TG
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ __('Gábor Tóth') }}</div>
                            <div class="text-sm text-gray-500">{{ __('Logistics Manager, Manufacturing company') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 2 --}}
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6">
                        {{ __('Since the system automatically alerts when ordering is needed, there have been no stockouts. Our suppliers are also happier with the predictability.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-semibold">
                            KM
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ __('Márton Kiss') }}</div>
                            <div class="text-sm text-gray-500">{{ __('Procurement Manager, Trading company') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial 3 --}}
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div class="flex items-center gap-1 mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6">
                        {{ __('Barcode receiving reduced inventory counting to a fraction. What used to take an entire weekend is now done in half a day.') }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-semibold">
                            SB
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ __('Béla Szabó') }}</div>
                            <div class="text-sm text-gray-500">{{ __('Warehouse Manager, Industrial supplier') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ==================== --}}
    {{-- 8. PRICING SECTION --}}
    {{-- ==================== --}}
    @if(false)
    <section id="arak" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Simple, transparent pricing') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('Choose the package tailored to your company. Every package includes a 14-day free trial.') }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Starter Tier --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Starter') }}</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">{{ __('5,900 Ft') }}</span>
                        <span class="text-gray-500">{{ __('/month') }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">{{ __('For small warehouses') }}</p>
                    <ul class="space-y-3 mb-8">
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('1 warehouse') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('1,000 SKUs') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Basic reports') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Stock alerts') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Email support') }}</x-landing.check-item>
                    </ul>
                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-amber-600 border-2 border-amber-200 rounded-full hover:bg-amber-50 transition-colors">
                        {{ __('Try it') }}
                    </a>
                </div>

                {{-- Professional Tier (Featured) --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-amber-500 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-amber-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            {{ __('Most popular') }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Professional') }}</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">{{ __('11,900 Ft') }}</span>
                        <span class="text-gray-500">{{ __('/month') }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">{{ __('For growing companies') }}</p>
                    <ul class="space-y-3 mb-8">
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Multiple warehouses') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Unlimited SKUs') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Automatic ordering') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Webshop integration') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Priority support') }}</x-landing.check-item>
                    </ul>
                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-white bg-amber-600 rounded-full hover:bg-amber-700 transition-colors">
                        {{ __('Start now') }}
                    </a>
                </div>

                {{-- Enterprise Tier --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Enterprise') }}</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">{{ __('Custom') }}</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">{{ __('For large enterprises') }}</p>
                    <ul class="space-y-3 mb-8">
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('All Professional features') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('EDI supplier connection') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Multi-site support') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('Custom workflows') }}</x-landing.check-item>
                        <x-landing.check-item class="text-sm text-gray-600">{{ __('SLA guarantee') }}</x-landing.check-item>
                    </ul>
                    <a href="#" class="block w-full py-3 text-center text-sm font-medium text-gray-700 border-2 border-gray-200 rounded-full hover:bg-gray-50 transition-colors">
                        {{ __('Request a quote') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ==================== --}}
    {{-- 9. FAQ SECTION --}}
    {{-- ==================== --}}
    <section id="gyik" class="py-16 lg:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    {{ __('Frequently asked questions') }}
                </h2>
            </div>

            <div class="space-y-4">
                <x-landing.faq-item :question="__('How can I import my existing inventory data?')">
                    {{ __('You can easily import from Excel or CSV files. The system recognizes columns and helps with mapping. You can load thousands of items in minutes.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('Does it support multi-warehouse operations?')">
                    {{ __('Yes, the Professional and Enterprise packages support unlimited warehouses. You can define separate locations (shelf, row, level) in each warehouse.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('How does barcode receiving work?')">
                    {{ __('With the mobile app you can use your phone camera as a barcode scanner. Simply scan the product, enter the quantity, and the stock updates instantly.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('Can it connect to my webshop?')">
                    {{ __('Yes, the Professional package includes WooCommerce and Shopify integration. Stock levels sync automatically, so your webshop always shows real inventory.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('How do automatic alerts work?')">
                    {{ __('You can set a minimum stock level for each product. When stock drops below this, the system automatically sends email notifications to designated people and generates order suggestions.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('Does it handle expiry dates and serial numbers?')">
                    {{ __('Yes, the system supports batch management, serial number tracking and expiry date records. It also sends automatic warnings about upcoming expirations.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('Can it be used without other Cégem360 modules?')">
                    {{ __('Yes, the Procurement-logistics module can be used standalone. However, it delivers the most value when combined with the Manufacturing and Sales modules, where data flows automatically.') }}
                </x-landing.faq-item>

                <x-landing.faq-item :question="__('What support do I get for implementation?')">
                    {{ __('Every package includes email support, knowledge base access and video tutorials. Professional and Enterprise packages also include personal onboarding and data migration assistance.') }}
                </x-landing.faq-item>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 10. CTA SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-gradient-to-r from-amber-600 to-orange-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-semibold text-white mb-4 font-heading">
                {{ __('Ready to bring order to your warehouse?') }}
            </h2>
            <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">
                {{ __('Discover how Cégem360 helps your company grow. No long-term commitment.') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="https://cegem360.eu/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-amber-600 bg-white rounded-full hover:bg-gray-100 transition-colors shadow-lg">
                    {{ __('Get started') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="https://cegem360.eu/kapcsolat" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-colors">
                    {{ __('Request a demo') }}
                </a>
                <a href="/login" class="inline-flex items-center gap-1 text-sm font-medium text-white/80 hover:text-white transition-colors">
                    {{ __('Log in to the app') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-layouts.footer />
</x-layouts.app>
