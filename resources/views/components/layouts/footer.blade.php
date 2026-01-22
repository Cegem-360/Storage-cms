<footer class="bg-white border-t border-gray-200" x-data="{ openSection: null }">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-8">
            <!-- Column 1: Logo + Links -->
            <div class="col-span-2 md:col-span-3 lg:col-span-1">
                <a href="/" class="flex items-center gap-2 mb-5">
                    <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-amber-600">Beszerzés</span>
                </a>
                <p class="text-sm text-gray-600 mb-4">
                    Moduláris vállalatirányítási platform magyar ipari cégeknek. Kontrolling, CRM, értékesítés, beszerzés, gyártás és automatizálás - egy helyen.
                </p>
                <ul class="space-y-2.5 text-sm text-gray-700">
                    <li><a href="#arak" class="text-inherit! hover:text-amber-600! transition-colors">Árak</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Kapcsolat</a></li>
                    <li><a href="#gyik" class="text-inherit! hover:text-amber-600! transition-colors">GYIK</a></li>
                </ul>
            </div>

            <!-- Column 2: Modulok -->
            <div class="col-span-1">
                <button
                    class="lg:hidden w-full flex items-center justify-between text-[15px] font-semibold text-gray-900 mb-4"
                    @click="openSection = openSection === 'modulok' ? null : 'modulok'"
                >
                    Modulok
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSection === 'modulok' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <h3 class="hidden lg:block text-[15px] font-semibold text-gray-900 mb-4">Modulok</h3>
                <ul class="space-y-2.5 text-sm text-gray-700" x-show="openSection === 'modulok' || window.innerWidth >= 1024" x-collapse.duration.300ms>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">CRM</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Kontrolling</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Értékesítés</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Beszerzés</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Gyártás</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Automatizálás</a></li>
                </ul>
            </div>

            <!-- Column 3: Cég -->
            <div class="col-span-1">
                <button
                    class="lg:hidden w-full flex items-center justify-between text-[15px] font-semibold text-gray-900 mb-4"
                    @click="openSection = openSection === 'ceg' ? null : 'ceg'"
                >
                    Cég
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSection === 'ceg' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <h3 class="hidden lg:block text-[15px] font-semibold text-gray-900 mb-4">Cég</h3>
                <ul class="space-y-2.5 text-sm text-gray-700" x-show="openSection === 'ceg' || window.innerWidth >= 1024" x-collapse.duration.300ms>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Rólunk</a></li>
                    <li><a href="#arak" class="text-inherit! hover:text-amber-600! transition-colors">Árak</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Referenciák</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Partnerek</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Karrier</a></li>
                </ul>
            </div>

            <!-- Column 4: Segítség -->
            <div class="col-span-1">
                <button
                    class="lg:hidden w-full flex items-center justify-between text-[15px] font-semibold text-gray-900 mb-4"
                    @click="openSection = openSection === 'segitseg' ? null : 'segitseg'"
                >
                    Segítség
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSection === 'segitseg' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <h3 class="hidden lg:block text-[15px] font-semibold text-gray-900 mb-4">Segítség</h3>
                <ul class="space-y-2.5 text-sm text-gray-700" x-show="openSection === 'segitseg' || window.innerWidth >= 1024" x-collapse.duration.300ms>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Súgó</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Tudásbázis</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Kapcsolat</a></li>
                    <li><a href="#gyik" class="text-inherit! hover:text-amber-600! transition-colors">GYIK</a></li>
                </ul>
            </div>

            <!-- Column 5: Jogi -->
            <div class="col-span-1">
                <button
                    class="lg:hidden w-full flex items-center justify-between text-[15px] font-semibold text-gray-900 mb-4"
                    @click="openSection = openSection === 'jogi' ? null : 'jogi'"
                >
                    Jogi
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSection === 'jogi' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <h3 class="hidden lg:block text-[15px] font-semibold text-gray-900 mb-4">Jogi</h3>
                <ul class="space-y-2.5 text-sm text-gray-700" x-show="openSection === 'jogi' || window.innerWidth >= 1024" x-collapse.duration.300ms>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">ÁSZF</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Adatvédelem</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Cookie szabályzat</a></li>
                    <li><a href="#" class="text-inherit! hover:text-amber-600! transition-colors">Impresszum</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sub-footer -->
    <div class="border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                <!-- Left: Social icons -->
                <div class="flex flex-col sm:flex-row items-center gap-5">
                    <!-- Social Icons -->
                    <div class="flex items-center gap-3">
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom row: Legal links -->
            <div class="mt-5 pt-5 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-sm text-gray-500">
                    <span class="text-gray-700">Cégem360 Beszerzés a Cégem360 Kft. terméke.</span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-sm text-gray-500">
                    <a href="#" class="hover:text-gray-700 transition-colors">ÁSZF</a>
                    <span class="text-gray-300">|</span>
                    <a href="#" class="hover:text-gray-700 transition-colors">Adatvédelem</a>
                    <span class="text-gray-300">|</span>
                    <a href="#" class="hover:text-gray-700 transition-colors">Cookie szabályzat</a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-3 text-center sm:text-left text-sm text-gray-400">
                Minden jog fenntartva. &copy; {{ date('Y') }} Cégem360
            </div>
        </div>
    </div>
</footer>
