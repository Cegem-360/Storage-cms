<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Beszerzés-logisztika | Cégem360 - Raktár és készletek egy rendszerben' }}</title>
        <meta name="description" content="{{ $description ?? 'Készletnyilvántartás, beszállító-kezelés és szállítmánykövetés egy átlátható rendszerben. Automatikus készletriasztások, beszerzési javaslatok. 14 napos ingyenes próba.' }}">

        <!-- Open Graph -->
        <meta property="og:title" content="{{ $title ?? 'Beszerzés-logisztika | Cégem360' }}">
        <meta property="og:description" content="Tudja mindig, mi van raktáron, mi van úton, és mikor kell rendelni. Automatikus újrarendelési javaslatok és készletoptimalizálás.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|poppins:400,500,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @filamentStyles
        @livewireStyles

        <style>
            [x-cloak] { display: none !important; }

            .font-heading {
                font-family: 'Poppins', sans-serif;
            }
        </style>
    </head>

    <body class="antialiased bg-white">
        <div class="min-h-screen">
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
        @filamentScripts
    </body>

</html>
