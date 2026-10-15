<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Struktur Lengkap - {{ $pengaturan['situs_nama'] }}</title>

        <link rel="icon" href="{{ asset('images/logo-staf-batalyon.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-tulang">
        <x-navbar-beranda :pengaturan="$pengaturan" />

        <header class="bg-laut-900 text-white">
            <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-14">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-laut-100 transition hover:text-white">
                    <span aria-hidden="true">&larr;</span> Kembali ke beranda
                </a>
                <h1 class="mt-3 font-judul text-4xl font-bold tracking-tight sm:text-5xl">Struktur Lengkap</h1>
                <p class="mt-2 max-w-2xl text-base text-laut-100">{{ $pengaturan['struktur_subjudul'] }}</p>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            @if ($bagan->isEmpty())
                <x-empty title="Belum ada data staf" ikon="staf" />
            @else
                <div class="overflow-x-auto">
                    <div class="bagan mx-auto w-max px-2 py-4">
                        <ul>
                            @foreach ($bagan as $simpul)
                                <x-bagan-node :node="$simpul" :tampil-bidang="true" />
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </main>

        <x-footer-beranda :pengaturan="$pengaturan" />
    </body>
</html>
