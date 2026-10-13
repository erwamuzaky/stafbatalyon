<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Staf Batalyon - SMKN 3 Pariaman</title>

        <link rel="icon" href="{{ asset('images/logo-staf-batalyon.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes sambut-naik {
                from { opacity: 0; transform: translateY(14px); }
                to { opacity: 1; transform: none; }
            }
            .animasi-muat { animation: sambut-naik .6s ease-out both; }
        </style>
    </head>
    <body>
        {{-- Navbar --}}
        <nav class="sticky top-0 z-20 border-b border-gray-200 bg-white">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-12 w-12 rounded-full border border-gray-200 object-cover">
                    <span class="leading-tight">
                        <span class="block font-judul text-lg font-bold text-arang">Staf Batalyon</span>
                        <span class="block text-sm text-arang/70">SMKN 3 Pariaman</span>
                    </span>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-utama">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sekunder">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-utama">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- Hero: latar tulang + satu blok laut tegas --}}
        <header class="border-b border-gray-200">
            <div class="mx-auto grid max-w-6xl items-stretch gap-6 px-4 py-10 sm:px-6 sm:py-14 lg:grid-cols-[minmax(0,11fr)_minmax(0,9fr)] lg:gap-10">
                <div class="animasi-muat flex flex-col justify-center">
                    <h1 class="font-judul text-5xl font-bold leading-none text-arang sm:text-6xl lg:text-7xl">Staf Batalyon</h1>
                    <p class="mt-2 font-judul text-2xl font-semibold text-laut-600 sm:text-3xl">SMKN 3 Pariaman</p>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-arang/85">
                        Organisasi kesiswaan yang menjaga ketertiban harian sekolah dan
                        menjalankan kegiatan serta kepanitiaan sepanjang tahun ajaran.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-utama">
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-utama">
                                Daftar
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-sekunder">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="flex flex-col justify-center rounded-2xl border-t-4 border-emas-500 bg-laut-700 px-6 py-8 text-white sm:px-8">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white p-1">
                            <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-10 w-10 rounded-full object-cover">
                        </span>
                        <div>
                            <p class="font-judul text-2xl font-bold leading-tight">Staf Batalyon</p>
                            <p class="text-sm text-laut-100">SMKN 3 Pariaman</p>
                        </div>
                    </div>
                    <span class="mt-5 h-1 w-20 rounded-full bg-emas-500" aria-hidden="true"></span>
                    <p class="mt-3 max-w-md text-base leading-relaxed text-laut-50">
                        Satu wadah pengurus dan staf: piket gerbang harian, arsip proposal
                        kegiatan, dan data keanggotaan yang tertib.
                    </p>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-12">
            {{-- Formasi staf inti: jabatan menonjol, nama di bawah, bidang kecil --}}
            <section>
                <h2 class="text-3xl font-bold text-arang sm:text-4xl">Struktur Staf</h2>
                <p class="mt-2 max-w-2xl text-base text-arang/75">Staf inti yang memimpin kepengurusan periode berjalan.</p>

                @php
                    $daftarInti = ($stafInti ?? collect());
                    $pemimpin = $daftarInti->first();
                    $pengurus = $daftarInti->skip(1);
                @endphp

                @if ($daftarInti->isNotEmpty() && $pemimpin)
                    <div class="mx-auto mt-6 max-w-xl rounded-2xl border-t-4 border-emas-500 bg-laut-700 px-6 py-8 text-center text-white">
                        <p class="font-judul text-4xl font-bold leading-tight sm:text-5xl">{{ $pemimpin->stafProfile?->jabatan ?? '-' }}</p>
                        <p class="mt-3 text-lg font-semibold">{{ $pemimpin->name }}</p>
                        <p class="mt-1 text-sm text-laut-100">{{ $pemimpin->stafProfile?->bidang?->nama ?? '-' }}</p>
                    </div>

                    @if ($pengurus->isNotEmpty())
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($pengurus as $user)
                                <div class="rounded-xl border-l-4 border-emas-500 border-y border-r border-gray-200 bg-white px-5 py-4">
                                    <p class="font-judul text-2xl font-bold leading-tight text-laut-700">{{ $user->stafProfile?->jabatan ?? '-' }}</p>
                                    <p class="mt-1 text-base font-semibold text-arang">{{ $user->name }}</p>
                                    <p class="mt-0.5 text-sm text-arang/65">{{ $user->stafProfile?->bidang?->nama ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="mt-6 rounded-xl border border-gray-200 bg-white px-5 py-6 text-base text-arang/70">
                        Belum ada data staf inti.
                    </div>
                @endif
            </section>

            {{-- Daftar bidang yang rapi --}}
            <section class="mt-12">
                <h2 class="text-3xl font-bold text-arang sm:text-4xl">Bidang</h2>
                <p class="mt-2 max-w-2xl text-base text-arang/75">Bidang-bidang yang menaungi seluruh staf batalyon.</p>

                @if (($bidang ?? collect())->isNotEmpty())
                    <ul class="mt-6 max-w-3xl divide-y divide-gray-200 rounded-xl border border-gray-200 bg-white">
                        @foreach (($bidang ?? collect()) as $item)
                            <li class="flex items-center gap-3 px-5 py-3.5">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-sm bg-emas-500" aria-hidden="true"></span>
                                <span class="text-base font-medium text-arang">{{ $item->nama ?? '-' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="mt-6 max-w-3xl rounded-xl border border-gray-200 bg-white px-5 py-6 text-base text-arang/70">
                        Belum ada data bidang.
                    </div>
                @endif
            </section>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-6 text-center text-sm text-arang/70 sm:px-6">
                Staf Batalyon SMKN 3 Pariaman &middot; &copy; {{ date('Y') }}
            </div>
        </footer>
    </body>
</html>
