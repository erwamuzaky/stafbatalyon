@props(['judul' => null, 'sambutan' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $judul ? $judul . ' - ' : '' }}{{ config('app.name', 'Staf Batalyon') }}</title>

        <link rel="icon" href="{{ asset('images/logo-staf-batalyon.jpg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="min-h-screen lg:grid lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">

            {{-- Panel kiri: laut solid dengan hiasan bintang, menampilkan logo, nama, dan sambutan --}}
            <aside class="relative h-full w-full overflow-hidden bg-laut-700 text-white">
                <x-hiasan-auth class="text-white/[0.07]" />

                <div class="relative mx-auto flex h-full max-w-md items-center gap-3 px-4 py-4 sm:px-6 lg:mx-0 lg:ml-auto lg:max-w-sm lg:flex-col lg:items-start lg:justify-center lg:gap-5 lg:px-10">
                    {{-- Lingkaran putih dengan logo (logo mengisi lingkaran dengan padding kecil, cincin emas di sekelilingnya) --}}
                    <div class="relative flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-white p-1 shadow-md">
                        <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-14 w-14 rounded-full object-contain p-1">
                        <div class="absolute inset-0 rounded-full border-2 border-emas-500/50"></div>
                    </div>

                    {{-- Logo dan "Staf Batalyon / SMKN 3 Pariaman" di atas kiri --}}
                    <div class="min-w-0">
                        <p class="font-judul text-xl font-bold leading-tight sm:text-2xl lg:text-3xl">Staf Batalyon</p>
                        <p class="text-sm text-laut-100 lg:text-base">SMKN 3 Pariaman</p>
                    </div>

                    {{-- Sambutan besar (font condensed) di bagian bawah kiri --}}
                    <div class="hidden max-w-xs lg:block">
                        @if ($sambutan)
                            <p class="text-2xl font-light leading-tight text-laut-100">{{ $sambutan }}</p>
                        @endif
                    </div>

                    {{-- Garis emas pendek sebagai pemisah (hanya di desktop) --}}
                    <span class="hidden h-1 w-24 rounded-full bg-emas-500 lg:block" aria-hidden="true"></span>
                </div>
            </aside>

            {{-- Panel kanan: form dengan latar belakang putih dan bayangan --}}
            <main class="flex items-start justify-center px-4 py-8 sm:px-6 lg:items-center lg:py-12">
                <div class="w-full max-w-md">
                    <div class="rounded-2xl border border-laut-100 bg-white p-6 shadow-soft-md sm:p-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-4 text-center text-sm text-arang/70">
                        Staf Batalyon SMKN 3 Pariaman
                    </p>
                </div>
            </main>
        </div>
    </body>
</html>
