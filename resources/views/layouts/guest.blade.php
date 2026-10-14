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

            {{-- Panel kiri: laut solid dengan hiasan bintang; isi terpusat, tinggi penuh saat jadi kolom di desktop --}}
            <aside class="relative flex items-center justify-center overflow-hidden bg-laut-700 p-6 text-white lg:sticky lg:top-0 lg:h-screen lg:p-12">
                <x-hiasan-auth class="text-white/[0.07]" />

                {{-- Satu wadah isi: lebar dibatasi, selalu rata tengah --}}
                <div class="relative mx-auto flex w-full max-w-sm flex-col items-center gap-6 text-center">
                    {{-- Lingkaran putih 96px, logo mengisi lingkaran dengan padding kecil, cincin emas tipis di sekelilingnya --}}
                    <span class="mx-auto flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-white p-1 shadow-soft-md ring-1 ring-emas-500/60">
                        <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-full w-full rounded-full object-contain p-1">
                    </span>

                    {{-- Nama dan sekolah --}}
                    <div>
                        <p class="font-judul text-2xl font-bold leading-tight lg:text-3xl">Staf Batalyon</p>
                        <p class="mt-1 text-sm text-laut-100 lg:text-base">SMKN 3 Pariaman</p>
                    </div>

                    {{-- Garis emas pendek sebagai pemisah --}}
                    <span class="mx-auto block h-1 w-24 rounded-full bg-emas-500" aria-hidden="true"></span>

                    {{-- Kalimat sambutan (disembunyikan di header HP agar tetap ringkas) --}}
                    @if ($sambutan)
                        <p class="hidden font-judul text-xl font-medium leading-snug text-white lg:block lg:text-2xl">{{ $sambutan }}</p>
                    @endif
                </div>
            </aside>

            {{-- Panel kanan: latar putih, kartu form di tengah vertikal --}}
            <main class="flex items-center justify-center bg-white px-4 py-8 sm:px-6 lg:min-h-screen lg:py-12">
                <div class="w-full max-w-[520px]">
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
