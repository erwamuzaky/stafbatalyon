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

        {{-- Header + bagan memakai foto latar paralaks sendiri (kunci
             "struktur_lengkap_background"); kalau kosong, memakai background bagian.
             Pembungkus luar berwarna gelap sebagai alas, jadi tepi clip yang jatuh
             di pecahan piksel tidak memunculkan garis terang. --}}
        <div @class(['bg-laut-900' => $bagianBackground])>
            <div @class(['relative [clip-path:inset(0)]' => $bagianBackground])>
                @if ($bagianBackground)
                    {{-- Lapisan foto fixed lalu dipotong clip-path, supaya parallax tetap jalan di HP (iOS tidak mendukung background-attachment: fixed). --}}
                    <div class="pointer-events-none fixed inset-0 bg-cover bg-center" style="background-image: url('{{ $bagianBackground }}');" aria-hidden="true"></div>
                    {{-- Tingkat kegelapan lapisan gelap mengikuti pengaturan admin. --}}
                    <x-latar-lapisan :kepekatan="$pengaturan['latar_kepekatan']" />
                @endif

                <header @class(['relative text-white', 'bg-laut-900' => ! $bagianBackground])>
                    @if ($bagianBackground)
                        {{-- Lapisan gelap lembut hanya di belakang judul: fotonya tetap terlihat, tulisan tetap enak dibaca. --}}
                        <div class="pointer-events-none absolute inset-x-0 top-0 h-52 bg-gradient-to-b from-transparent via-laut-900/80 to-transparent" aria-hidden="true"></div>
                    @endif

                    <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-14">
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-laut-100 transition hover:text-white">
                            <span aria-hidden="true">&larr;</span> Kembali ke beranda
                        </a>
                        <h1 class="mt-3 font-judul text-4xl font-bold tracking-tight sm:text-5xl">Struktur Lengkap</h1>
                        <p class="mt-2 max-w-2xl text-base text-laut-100">{{ $pengaturan['struktur_subjudul'] }}</p>
                    </div>
                </header>

                <main class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
                    @if ($bagan->isEmpty())
                        <x-empty title="Belum ada data staf" ikon="staf" />
                    @else
                        <div class="overflow-x-auto">
                            <div @class(['bagan mx-auto w-max px-2 py-4', 'bagan-terang' => $bagianBackground])>
                                <ul>
                                    @foreach ($bagan as $simpul)
                                        <x-bagan-node :node="$simpul" />
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>

        <x-footer-beranda :pengaturan="$pengaturan" />
    </body>
</html>
