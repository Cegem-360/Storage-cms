<x-layouts.app>
    {{-- Navbar --}}
    <x-layouts.navbar />

    {{-- ==================== --}}
    {{-- 1. HERO SECTION --}}
    {{-- ==================== --}}
    <section class="bg-gradient-to-b from-amber-50 to-white pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Új: Vonalkódos bevételezés mobilon
                </div>

                {{-- H1 --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-gray-900 mb-6 font-heading leading-tight">
                    Raktárkészlet és szállítások egy átlátható rendszerben
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Tudja mindig, mi van raktáron, mi van úton, és mikor kell rendelni. Vessen véget az Excel-káosznak és a készlethiányoknak.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    <a href="/admin" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white bg-amber-600 rounded-full hover:bg-amber-700 transition-colors shadow-lg hover:shadow-xl">
                        Próbálja ki 14 napig ingyen
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-amber-700 bg-white border-2 border-amber-200 rounded-full hover:bg-amber-50 transition-colors">
                        Demó kérése
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Több raktár támogatása
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Webshop integráció
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Magyar ügyfélszolgálat
                    </span>
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
                    <div class="relative bg-gradient-to-br from-gray-50 via-amber-50/20 to-gray-50 p-6 lg:p-8">
                        {{-- Grid pattern --}}
                        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(circle, #6b7280 1px, transparent 1px); background-size: 24px 24px;"></div>

                        <div class="relative" style="z-index: 2;">
                            {{-- Stat boxes --}}
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 animate-float">
                                    <div class="text-2xl font-bold text-gray-900 mb-1">1,247</div>
                                    <div class="text-sm text-gray-500">Összes SKU</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-amber-200 animate-float-delayed">
                                    <div class="text-2xl font-bold text-amber-600 mb-1">12</div>
                                    <div class="text-sm text-amber-600">Alacsony készlet</div>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 animate-float-delayed-2">
                                    <div class="text-2xl font-bold text-blue-600 mb-1">8</div>
                                    <div class="text-sm text-gray-500">Úton lévő</div>
                                </div>
                            </div>

                            {{-- Stock list --}}
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <span class="font-semibold text-gray-900">Készlet áttekintés</span>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <div class="px-4 py-3 flex items-center justify-between">
                                        <div>
                                            <div class="font-medium text-gray-900">M8 csavar 50mm</div>
                                            <div class="text-sm text-gray-500">SKU-2847</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">2,450 db</div>
                                            <div class="text-sm text-emerald-600 flex items-center gap-1 justify-end">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                                Rendben
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 flex items-center justify-between bg-amber-50/50">
                                        <div>
                                            <div class="font-medium text-gray-900">Golyóscsapágy 6205</div>
                                            <div class="text-sm text-gray-500">SKU-1093</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">45 db</div>
                                            <div class="text-sm text-amber-600 flex items-center gap-1 justify-end">
                                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                                Alacsony
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 flex items-center justify-between bg-red-50/50">
                                        <div>
                                            <div class="font-medium text-gray-900">Acél lemez 2mm</div>
                                            <div class="text-sm text-gray-500">SKU-0521</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">8 tábla</div>
                                            <div class="text-sm text-red-600 flex items-center gap-1 justify-end">
                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                                Kritikus
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
                                <span class="text-gray-700 font-medium">Szállítmány megérkezett!</span>
                                <span class="text-gray-500">TRANS-2024-0892</span>
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/90 backdrop-blur border border-amber-200 rounded-full text-sm shadow-md">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <span class="text-gray-700 font-medium">Rendelési javaslat</span>
                                <span class="text-amber-600">3 termék minimum alatt</span>
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
                    Ismeri ezeket a problémákat?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    A legtöbb ipari cég küzd a készletkezelés és a beszerzés átláthatóságával.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                {{-- Problem 1 --}}
                <div class="bg-gradient-to-br from-red-50 to-white rounded-2xl p-6 border border-red-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Excel-káosz</h3>
                    <p class="text-gray-600 text-sm">
                        Több táblázat, több verzió - senki nem tudja, melyik az aktuális készletlista.
                    </p>
                </div>

                {{-- Problem 2 --}}
                <div class="bg-gradient-to-br from-amber-50 to-white rounded-2xl p-6 border border-amber-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Készlethiány</h3>
                    <p class="text-gray-600 text-sm">
                        Mindig akkor derül ki, hogy elfogyott valami, amikor már sürgősen kellene.
                    </p>
                </div>

                {{-- Problem 3 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-6 border border-orange-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Elveszett rendelések</h3>
                    <p class="text-gray-600 text-sm">
                        A beszállítóval megbeszélték, de nem tudni, ki intézte és hol tart.
                    </p>
                </div>

                {{-- Problem 4 --}}
                <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-6 border border-purple-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Kézi leltározás</h3>
                    <p class="text-gray-600 text-sm">
                        Órákon át számolják a készletet, és akkor sem stimmel a végén.
                    </p>
                </div>
            </div>

            {{-- Solution --}}
            <div class="bg-amber-50 rounded-2xl p-8 lg:p-12 border border-amber-100 max-w-5xl mx-auto">
                <div class="flex flex-col lg:flex-row items-center gap-8">
                    <div class="flex-1">
                        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4 font-heading">A Cégem360 Beszerzés mindezt megoldja</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Végre egy hely, ahol látja az összes készletet, beszállítót és szállítmányt - valós időben, minden eszközön.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Valós idejű készletnyilvántartás
                            </li>
                            <li class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Automatikus készletriasztások
                            </li>
                            <li class="flex items-center gap-2 text-gray-700">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Beszállító-kezelés és értékelés
                            </li>
                        </ul>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-60%</div>
                            <div class="text-sm text-gray-600">Készlethiány csökkenés</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-70%</div>
                            <div class="text-sm text-gray-600">Leltározási idő</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-40%</div>
                            <div class="text-sm text-gray-600">Beszerzési ciklus</div>
                        </div>
                        <div class="bg-white rounded-xl p-4 text-center shadow-sm">
                            <div class="text-3xl font-bold text-amber-600 mb-1">-25%</div>
                            <div class="text-sm text-gray-600">Túlkészletezés</div>
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
                    Minden eszköz a hatékony készletkezeléshez
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    A Cégem360 Beszerzés-logisztika modul 6 kulcsterületen segíti a raktár és beszállítók kezelését.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: Készletkezelés --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Készletkezelés</h3>
                    <p class="text-gray-600 mb-4">
                        Valós idejű készletnyilvántartás több raktárban, automatikus riasztásokkal.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Több raktár és lokáció támogatása
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Minimumkészlet-riasztások
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Sorozatszám és lejárati dátum követés
                        </li>
                    </ul>
                </div>

                {{-- Feature 2: Beszerzés --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Beszerzés</h3>
                    <p class="text-gray-600 mb-4">
                        Beszállító-kezelés, ajánlatkérés és automatikus újrarendelési javaslatok.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Beszállítói adatbázis és értékelés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Árajánlat-kezelés és összehasonlítás
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Megrendelés-jóváhagyási workflow
                        </li>
                    </ul>
                </div>

                {{-- Feature 3: Szállítmánykövetés --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Szállítmánykövetés</h3>
                    <p class="text-gray-600 mb-4">
                        Bejövő és kimenő szállítások státusza egy helyen, valós időben.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Várható érkezési idők
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Szállítmány-riasztások késés esetén
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Fuvarozó-integráció
                        </li>
                    </ul>
                </div>

                {{-- Feature 4: Raktári műveletek --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Raktári műveletek</h3>
                    <p class="text-gray-600 mb-4">
                        Bevételezés, kiadás és leltározás - akár mobilon, vonalkóddal.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Bevételezés vonalkóddal vagy manuálisan
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Kiadás és átmozgatás
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Raktári lokáció-kezelés (polc, sor)
                        </li>
                    </ul>
                </div>

                {{-- Feature 5: Riportok --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Riportok és elemzések</h3>
                    <p class="text-gray-600 mb-4">
                        ABC-elemzés, forgási mutatók és készletérték követése.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Készletforgási mutatók
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            ABC-elemzés
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Beszállítói teljesítmény riport
                        </li>
                    </ul>
                </div>

                {{-- Feature 6: Integrációk --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Integrációk</h3>
                    <p class="text-gray-600 mb-4">
                        Webshop-szinkronizáció, ERP-kapcsolat és vonalkód-olvasók támogatása.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            WooCommerce, Shopify integráció
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Vonalkód-olvasók támogatása
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            EDI beszállítói kapcsolat
                        </li>
                    </ul>
                </div>
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
                    Automatikus újrarendelés 7 lépésben
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    A rendszer figyeli a készleteket és automatikusan javasolja a rendelést - Ön csak jóváhagyja.
                </p>
            </div>

            {{-- Workflow Steps --}}
            <div class="relative max-w-4xl mx-auto">
                {{-- Connecting line --}}
                <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-amber-200 -translate-x-1/2"></div>

                <div class="space-y-8">
                    {{-- Step 1 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:text-right order-2 lg:order-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Készlet csökken</h3>
                            <p class="text-gray-600 text-sm">A rendszer érzékeli a minimum szint alatti készletet</p>
                        </div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:opacity-0"><!-- Spacer --></div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:opacity-0 order-2 lg:order-1"><!-- Spacer --></div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:text-left">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Riasztás</h3>
                            <p class="text-gray-600 text-sm">Automatikus értesítés a beszerzőnek</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:text-right order-2 lg:order-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Javaslat</h3>
                            <p class="text-gray-600 text-sm">Rendelési javaslat a preferált beszállítóval</p>
                        </div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:opacity-0"><!-- Spacer --></div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:opacity-0 order-2 lg:order-1"><!-- Spacer --></div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:text-left">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Jóváhagyás</h3>
                            <p class="text-gray-600 text-sm">Egy kattintás vagy automatikus feldolgozás</p>
                        </div>
                    </div>

                    {{-- Step 5 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:text-right order-2 lg:order-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Megrendelés</h3>
                            <p class="text-gray-600 text-sm">E-mail vagy EDI a beszállítónak</p>
                        </div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:opacity-0"><!-- Spacer --></div>
                    </div>

                    {{-- Step 6 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:opacity-0 order-2 lg:order-1"><!-- Spacer --></div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:text-left">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Követés</h3>
                            <p class="text-gray-600 text-sm">Szállítmány státusza a dashboardon</p>
                        </div>
                    </div>

                    {{-- Step 7 --}}
                    <div class="relative flex flex-col lg:flex-row items-center gap-6">
                        <div class="flex-1 lg:text-right order-2 lg:order-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Bevételezés</h3>
                            <p class="text-gray-600 text-sm">Vonalkóddal vagy manuálisan, készlet frissül</p>
                        </div>
                        <div class="relative z-10 order-1 lg:order-2">
                            <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 order-3 lg:opacity-0"><!-- Spacer --></div>
                    </div>
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
                    Összekapcsolva a teljes ökoszisztémával
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    A Beszerzés modul együttműködik a webshopokkal, ERP rendszerekkel és a többi Cégem360 modullal.
                </p>
            </div>

            {{-- Integration Hub --}}
            <div class="relative max-w-4xl mx-auto">
                <div class="flex flex-wrap justify-center items-center gap-4">
                    {{-- WooCommerce --}}
                    <div class="bg-gradient-to-br from-purple-50 to-white rounded-xl p-4 border border-purple-200 w-36 text-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">WooCommerce</span>
                    </div>

                    {{-- Shopify --}}
                    <div class="bg-gradient-to-br from-green-50 to-white rounded-xl p-4 border border-green-200 w-36 text-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Shopify</span>
                    </div>

                    {{-- Gyártás --}}
                    <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl p-4 border border-indigo-200 w-36 text-center">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Gyártás</span>
                    </div>
                </div>

                {{-- Center - Beszerzés --}}
                <div class="flex justify-center my-6">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-center shadow-xl">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span class="text-white font-semibold">Beszerzés</span>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center items-center gap-4">
                    {{-- Értékesítés --}}
                    <div class="bg-gradient-to-br from-red-50 to-white rounded-xl p-4 border border-red-200 w-36 text-center">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Értékesítés</span>
                    </div>

                    {{-- Kontrolling --}}
                    <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl p-4 border border-emerald-200 w-36 text-center">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Kontrolling</span>
                    </div>

                    {{-- Vonalkód --}}
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 border border-gray-200 w-36 text-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Vonalkód</span>
                    </div>
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
                    Amit ügyfeleink elértek a Beszerzés modullal
                </h2>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    Valós számok, valós ügyfelektől. Az átlagos megtérülés 2-4 hónap.
                </p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-60%</div>
                    <div class="text-sm text-gray-400">Készlethiányok száma</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-25%</div>
                    <div class="text-sm text-gray-400">Túlkészletezés</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-70%</div>
                    <div class="text-sm text-gray-400">Leltározási idő</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-40%</div>
                    <div class="text-sm text-gray-400">Beszerzési ciklus</div>
                </div>
                <div class="text-center col-span-2 lg:col-span-1">
                    <div class="text-4xl lg:text-5xl font-bold text-amber-400 mb-2">-50%</div>
                    <div class="text-sm text-gray-400">Admin munka</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 7. TESTIMONIALS SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Mit mondanak ügyfeleink?
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
                        "Korábban Excelben nyomon követni a készleteket rémálom volt. Most egy kattintás, és látom, mi hiányzik. Az automatikus riasztások óta nem volt készlethiány miatti leállás."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-semibold">
                            TG
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Tóth Gábor</div>
                            <div class="text-sm text-gray-500">Logisztikai vezető, Gyártó vállalat</div>
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
                        "Amióta a rendszer automatikusan jelez, ha rendelni kell, nem volt készlethiány. A beszállítóink is elégedettebbek a kiszámíthatósággal."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-semibold">
                            KM
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Kiss Márton</div>
                            <div class="text-sm text-gray-500">Beszerzési vezető, Kereskedelmi cég</div>
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
                        "A vonalkódos bevételezéssel a leltározás töredékére csökkent. Ami korábban egy teljes hétvége volt, az most fél nap alatt megvan."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-semibold">
                            SB
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Szabó Béla</div>
                            <div class="text-sm text-gray-500">Raktárvezető, Ipari beszállító</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 8. PRICING SECTION --}}
    {{-- ==================== --}}
    <section id="arak" class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Egyszerű, átlátható árak
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Válassza ki a cégére szabott csomagot. Minden csomag tartalmaz 14 napos ingyenes próbaidőszakot.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Starter Tier --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Starter</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">5 900 Ft</span>
                        <span class="text-gray-500">/hó</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Kis raktárak számára</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            1 raktár
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            1 000 SKU
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Alap riportok
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Készletriasztások
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            E-mail támogatás
                        </li>
                    </ul>
                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-amber-600 border-2 border-amber-200 rounded-full hover:bg-amber-50 transition-colors">
                        Kipróbálom
                    </a>
                </div>

                {{-- Professional Tier (Featured) --}}
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-amber-500 relative">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-amber-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            Legnépszerűbb
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Professional</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">11 900 Ft</span>
                        <span class="text-gray-500">/hó</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Növekvő cégek számára</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Több raktár
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Korlátlan SKU
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Automatikus rendelés
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Webshop integráció
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Prioritásos támogatás
                        </li>
                    </ul>
                    <a href="/admin" class="block w-full py-3 text-center text-sm font-medium text-white bg-amber-600 rounded-full hover:bg-amber-700 transition-colors">
                        Kezdés most
                    </a>
                </div>

                {{-- Enterprise Tier --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Enterprise</h3>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-gray-900">Egyedi</span>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Nagyvállalatok számára</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Minden Professional funkció
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            EDI beszállítói kapcsolat
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Multi-site támogatás
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Egyedi workflow-k
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            SLA garancia
                        </li>
                    </ul>
                    <a href="#" class="block w-full py-3 text-center text-sm font-medium text-gray-700 border-2 border-gray-200 rounded-full hover:bg-gray-50 transition-colors">
                        Ajánlat kérése
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 9. FAQ SECTION --}}
    {{-- ==================== --}}
    <section id="gyik" class="py-16 lg:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 mb-4 font-heading">
                    Gyakran ismételt kérdések
                </h2>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Hogyan importálhatom a meglévő készletadataimat?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Excel vagy CSV fájlból egyszerűen importálhat. A rendszer felismeri az oszlopokat és segít a megfeleltetésben. Akár több ezer tételt is percek alatt betölthet.
                        </div>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Támogatja a több raktáras működést?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, a Professional és Enterprise csomagok korlátlan számú raktárt támogatnak. Minden raktárban külön lokációkat (polc, sor, szint) is definiálhat.
                        </div>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Hogyan működik a vonalkódos bevételezés?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            A mobil alkalmazással a telefon kameráját használhatja vonalkód-olvasóként. Egyszerűen beolvassa a terméket, megadja a mennyiséget, és a készlet azonnal frissül.
                        </div>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Összeköthető a webshopommal?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, a Professional csomag tartalmazza a WooCommerce és Shopify integrációt. A készletek automatikusan szinkronizálódnak, így a webshopban mindig a valós készlet látható.
                        </div>
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Hogyan működnek az automatikus riasztások?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Minden terméknél beállíthat minimum készletszintet. Ha a készlet ez alá csökken, a rendszer automatikusan e-mail értesítést küld a megadott személyeknek, és rendelési javaslatot is készít.
                        </div>
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Kezeli a lejárati dátumokat és sorozatszámokat?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, a rendszer támogatja a sarzs-kezelést, sorozatszámok követését és a lejárati dátumok nyilvántartását. Automatikus figyelmeztetést is küld a közelgő lejáratokról.
                        </div>
                    </div>
                </div>

                {{-- FAQ 7 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Használható más Cégem360 modulok nélkül?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Igen, a Beszerzés-logisztika modul önállóan is használható. A legnagyobb értéket azonban a Gyártás és Értékesítés modulokkal kombinálva nyújtja, ahol az adatok automatikusan áramlanak.
                        </div>
                    </div>
                </div>

                {{-- FAQ 8 --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full px-6 py-4 text-left flex items-center justify-between">
                        <span class="font-medium text-gray-900">Milyen támogatást kapok a bevezetéshez?</span>
                        <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="px-6 pb-4 text-gray-600">
                            Minden csomag tartalmaz e-mail támogatást, tudásbázis hozzáférést és video oktatóanyagokat. A Professional és Enterprise csomagoknál személyes onboarding és adat-migráció segítség is jár.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== --}}
    {{-- 10. CTA SECTION --}}
    {{-- ==================== --}}
    <section class="py-16 lg:py-24 bg-gradient-to-r from-amber-600 to-orange-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-semibold text-white mb-4 font-heading">
                Készen áll rendet tenni a raktárban?
            </h2>
            <p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto">
                Kezdje el 14 napos ingyenes próbaidőszakkal. Nincs szükség bankkártyára, bármikor lemondható.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-amber-600 bg-white rounded-full hover:bg-gray-100 transition-colors">
                    Ingyenes próba indítása
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-colors">
                    Demó kérése
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-layouts.footer />
</x-layouts.app>
