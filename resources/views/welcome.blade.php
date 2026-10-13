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
        <nav class="sticky top-0 z-20 border border-laut-100/60 bg-white/95 shadow-soft-sm backdrop-blur supports-[backdrop-filter]:bg-white/80">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between rounded-2xl px-4 sm:px-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-12 w-12 rounded-full border border-laut-100 object-cover">
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
        <header class="relative overflow-hidden">
            <div class="absolute inset-x-0 top-0 -z-10 h-full bg-gradient-to-b from-white via-tulang/70 to-tulang"></div>
            <div class="pointer-events-none absolute -top-16 right-[-20%] -z-10 h-72 w-72 rounded-full bg-laut-50 blur-3xl md:right-[10%]"></div>
            <div class="pointer-events-none absolute -bottom-20 left-[-10%] -z-10 h-64 w-64 rounded-full bg-emas-50 blur-3xl md:left-[5%]"></div>

            <div class="mx-auto grid max-w-6xl items-stretch gap-6 px-4 py-10 sm:px-6 sm:py-14 lg:grid-cols-[minmax(0,11fr)_minmax(0,9fr)] lg:gap-10">
                <div class="flex flex-col justify-center">
                    <h1 class="font-judul text-5xl font-bold leading-none tracking-tight text-arang sm:text-6xl lg:text-7xl">Staf Batalyon</h1>
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

                <div class="flex flex-col justify-center rounded-2xl border border-laut-100 bg-white px-6 py-8 shadow-soft-md sm:px-8">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-laut-50 p-1 shadow-soft-sm">
                            <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-10 w-10 rounded-full object-cover">
                        </span>
                        <div>
                            <p class="font-judul text-2xl font-bold leading-tight">Staf Batalyon</p>
                            <p class="text-sm text-arang/70">SMKN 3 Pariaman</p>
                        </div>
                    </div>
                    <span class="mt-5 h-1 w-20 rounded-full bg-emas-500" aria-hidden="true"></span>
                    <p class="mt-3 max-w-md text-base leading-relaxed text-arang/80">
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
                    <div class="mx-auto mt-6 max-w-xl rounded-3xl border border-laut-100 bg-white px-6 py-8 text-center shadow-soft-md">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-laut-50 text-lg font-semibold text-laut-700">
                            {{ Str::substr($pemimpin->name, 0, 2) }}
                        </div>
                        <p class="mt-4 font-judul text-4xl font-bold leading-tight sm:text-5xl">{{ $pemimpin->stafProfile?->jabatan ?? '-' }}</p>
                        <p class="mt-3 text-lg font-semibold text-arang">{{ $pemimpin->name }}</p>
                        <span class="mt-2 inline-flex items-center rounded-full bg-info-50 px-3 py-1 text-xs font-medium text-info-700">
                            {{ $pemimpin->stafProfile?->bidang?->nama ?? '-' }}
                        </span>
                    </div>

                    @if ($pengurus->isNotEmpty())
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($pengurus as $user)
                                <div class="rounded-2xl border border-laut-100 bg-white px-5 py-4 shadow-soft-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-laut-50 text-sm font-semibold text-laut-700">
                                            {{ Str::substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-judul text-2xl font-bold leading-tight text-laut-700">{{ $user->stafProfile?->jabatan ?? '-' }}</p>
                                            <p class="mt-1 text-base font-semibold text-arang">{{ $user->name }}</p>
                                            <span class="mt-1 inline-flex items-center rounded-full bg-info-50 px-2.5 py-0.5 text-xs font-medium text-info-700">
                                                {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="empty-state mt-6">
                        Belum ada data staf inti.
                    </div>
                @endif
            </section>

            {{-- Daftar bidang yang rapi --}}
            <section class="mt-12">
                <h2 class="text-3xl font-bold text-arang sm:text-4xl">Bidang</h2>
                <p class="mt-2 max-w-2xl text-base text-arang/75">Bidang-bidang yang menaungi seluruh staf batalyon.</p>

                @if (($bidang ?? collect())->isNotEmpty())
                    <div class="mt-6 grid max-w-3xl gap-3 sm:grid-cols-2">
                        @foreach (($bidang ?? collect()) as $item)
                            <div class="flex items-center gap-3 rounded-2xl border border-laut-100 bg-white px-4 py-3 shadow-soft-sm">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emas-50">
                                    <svg class="h-5 w-5 text-emas-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                    </svg>
                                </span>
                                <span class="text-base font-medium text-arang">{{ $item->nama ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state mt-6 max-w-3xl">
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
