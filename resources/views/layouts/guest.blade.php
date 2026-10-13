@props(['judul' => null])

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

            {{-- Panel kiri: identitas, menyusut jadi bar atas di HP --}}
            <aside class="bg-laut-700 text-white">
                <div class="mx-auto flex max-w-md items-center gap-3 px-4 py-4 sm:px-6 lg:mx-0 lg:ml-auto lg:h-full lg:max-w-sm lg:flex-col lg:items-start lg:justify-center lg:gap-4 lg:px-10">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white p-1">
                        <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-10 w-10 rounded-full object-cover">
                    </span>
                    <div class="min-w-0">
                        <p class="font-judul text-xl font-bold leading-tight sm:text-2xl lg:text-3xl">Staf Batalyon</p>
                        <p class="text-sm text-laut-100 lg:text-base">SMKN 3 Pariaman</p>
                        <p class="mt-1 hidden max-w-xs text-sm leading-relaxed text-laut-100 lg:block">
                            Selamat datang. Masuk untuk melihat jadwal piket dan info kepengurusan Anda.
                        </p>
                    </div>
                    <span class="ml-auto hidden h-1 w-24 rounded-full bg-emas-500 lg:block" aria-hidden="true"></span>
                </div>
            </aside>

            {{-- Panel kanan: form --}}
            <main class="flex items-start justify-center px-4 py-8 sm:px-6 lg:items-center lg:py-12">
                <div class="w-full max-w-md">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-8">
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
