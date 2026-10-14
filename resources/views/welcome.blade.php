<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $pengaturan['situs_nama'] }} - {{ $pengaturan['situs_sekolah'] }}</title>

        <link rel="icon" href="{{ asset('images/logo-staf-batalyon.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        {{-- Navbar mengambang lembut --}}
        <div class="sticky top-0 z-30 px-3 pt-3 sm:px-4 sm:pt-4">
            <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 rounded-2xl border border-laut-100 bg-white/95 px-3 py-2 shadow-soft-sm backdrop-blur supports-[backdrop-filter]:bg-white/85 sm:px-4">
                <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2.5">
                    <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-9 w-9 shrink-0 rounded-full border border-laut-100 object-cover">
                    <span class="min-w-0 leading-tight">
                        <span class="block truncate font-judul text-base font-bold text-arang">{{ $pengaturan['situs_nama'] }}</span>
                        <span class="hidden text-xs text-arang/70 sm:block">{{ $pengaturan['situs_sekolah'] }}</span>
                    </span>
                </a>

                <div class="flex shrink-0 items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-utama">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sekunder">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-utama">Daftar</a>
                    @endauth
                </div>
            </nav>
        </div>

        {{-- Hero: latar tulang + satu blok laut besar --}}
        @if ($heroBackground)
            <header class="animasi-muat relative bg-cover bg-center" style="background-image: url('{{ $heroBackground }}');">
                <div class="absolute inset-0 bg-laut-900/70"></div>
                <div class="relative z-10 mx-auto grid max-w-6xl gap-8 px-4 pb-14 pt-10 sm:px-6 sm:pt-14 lg:grid-cols-[minmax(0,1fr)_minmax(0,440px)] lg:items-center lg:gap-12">
                    <div>
                        <p class="font-judul text-sm font-semibold uppercase tracking-[0.18em] text-emas-400">{{ $pengaturan['hero_label'] }}</p>
                        <h1 class="mt-2 font-judul text-5xl font-bold leading-[0.95] tracking-tight text-white sm:text-6xl lg:text-7xl">{{ $pengaturan['situs_nama'] }}</h1>
                        <p class="mt-2 font-judul text-2xl font-semibold text-laut-100 sm:text-3xl">{{ $pengaturan['situs_sekolah'] }}</p>
                        <p class="mt-5 max-w-xl text-base leading-relaxed text-white/90">
                            {{ $pengaturan['hero_pengantar'] }}
                        </p>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl bg-laut-700 p-6 text-white shadow-soft-lg sm:p-8">
                        <x-pola-bintang class="text-white/[0.07]" id="pola-hero" />

                        <div class="relative">
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-white p-1 shadow-soft-sm">
                                <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-12 w-12 rounded-full object-cover">
                            </span>
                            <p class="mt-5 font-judul text-2xl font-bold leading-tight">{{ $pengaturan['situs_nama'] }}</p>
                            <p class="mt-1 text-sm text-laut-100">{{ $pengaturan['situs_sekolah'] }}</p>
                            <span class="mt-5 block h-1 w-16 rounded-full bg-emas-500" aria-hidden="true"></span>

                            <div class="mt-6 grid grid-cols-3 gap-2.5">
                                <div class="rounded-2xl bg-white/10 px-3 py-2.5 ring-1 ring-inset ring-white/15">
                                    <p class="font-judul text-2xl font-bold leading-none sm:text-3xl">{{ $jumlahStafAktif }}</p>
                                    <p class="mt-1 text-[11px] leading-tight text-laut-100">Staf aktif</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 px-3 py-2.5 ring-1 ring-inset ring-white/15">
                                    <p class="font-judul text-2xl font-bold leading-none sm:text-3xl">{{ $jumlahBidang }}</p>
                                    <p class="mt-1 text-[11px] leading-tight text-laut-100">Bidang</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 px-3 py-2.5 ring-1 ring-inset ring-white/15">
                                    <p class="font-judul text-2xl font-bold leading-none sm:text-3xl">{{ $jumlahKegiatanTahunIni }}</p>
                                    <p class="mt-1 text-[11px] leading-tight text-laut-100">Kegiatan {{ now()->year }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @else
            <header class="animasi-muat bg-tulang">
                <div class="mx-auto grid max-w-6xl gap-8 px-4 pb-14 pt-10 sm:px-6 sm:pt-14 lg:grid-cols-[minmax(0,1fr)_minmax(0,440px)] lg:items-center lg:gap-12">
                    <div>
                        <p class="font-judul text-sm font-semibold uppercase tracking-[0.18em] text-emas-600">{{ $pengaturan['hero_label'] }}</p>
                        <h1 class="mt-2 font-judul text-5xl font-bold leading-[0.95] tracking-tight text-arang sm:text-6xl lg:text-7xl">{{ $pengaturan['situs_nama'] }}</h1>
                        <p class="mt-2 font-judul text-2xl font-semibold text-laut-600 sm:text-3xl">{{ $pengaturan['situs_sekolah'] }}</p>
                        <p class="mt-5 max-w-xl text-base leading-relaxed text-arang/80">
                            {{ $pengaturan['hero_pengantar'] }}
                        </p>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl bg-laut-700 p-6 text-white shadow-soft-lg sm:p-8">
                        <x-pola-bintang class="text-white/[0.07]" id="pola-hero" />

                        <div class="relative">
                            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-white p-1 shadow-soft-sm">
                                <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-12 w-12 rounded-full object-cover">
                            </span>
                            <p class="mt-5 font-judul text-2xl font-bold leading-tight">{{ $pengaturan['situs_nama'] }}</p>
                            <p class="mt-1 text-sm text-laut-100">{{ $pengaturan['situs_sekolah'] }}</p>
                            <span class="mt-5 block h-1 w-16 rounded-full bg-emas-500" aria-hidden="true"></span>

                            <div class="mt-6 grid grid-cols-3 gap-2.5">
                                <div class="rounded-2xl bg-white/10 px-3 py-2.5 ring-1 ring-inset ring-white/15">
                                    <p class="font-judul text-2xl font-bold leading-none sm:text-3xl">{{ $jumlahStafAktif }}</p>
                                    <p class="mt-1 text-[11px] leading-tight text-laut-100">Staf aktif</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 px-3 py-2.5 ring-1 ring-inset ring-white/15">
                                    <p class="font-judul text-2xl font-bold leading-none sm:text-3xl">{{ $jumlahBidang }}</p>
                                    <p class="mt-1 text-[11px] leading-tight text-laut-100">Bidang</p>
                                </div>
                                <div class="rounded-2xl bg-white/10 px-3 py-2.5 ring-1 ring-inset ring-white/15">
                                    <p class="font-judul text-2xl font-bold leading-none sm:text-3xl">{{ $jumlahKegiatanTahunIni }}</p>
                                    <p class="mt-1 text-[11px] leading-tight text-laut-100">Kegiatan {{ now()->year }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @endif

        {{-- Tentang: latar laut-900 penuh lebar --}}
        <section class="bg-laut-900 text-white">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
                <div class="max-w-2xl">
                    <p class="font-judul text-sm font-semibold uppercase tracking-[0.18em] text-emas-400">{{ $pengaturan['tentang_label'] }}</p>
                    <h2 class="mt-2 font-judul text-3xl font-bold sm:text-4xl">{{ $pengaturan['tentang_judul'] }}</h2>
                    <p class="mt-4 text-base leading-relaxed text-laut-100">
                        {{ $pengaturan['tentang_pengantar'] }}
                    </p>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-3">
                    <div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-emas-400">
                            <x-icon name="piket" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-judul text-lg font-semibold">{{ $pengaturan['tentang_fitur1_judul'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-laut-100">
                            {{ $pengaturan['tentang_fitur1_teks'] }}
                        </p>
                    </div>
                    <div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-emas-400">
                            <x-icon name="event" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-judul text-lg font-semibold">{{ $pengaturan['tentang_fitur2_judul'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-laut-100">
                            {{ $pengaturan['tentang_fitur2_teks'] }}
                        </p>
                    </div>
                    <div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-emas-400">
                            <x-icon name="dokumen" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-judul text-lg font-semibold">{{ $pengaturan['tentang_fitur3_judul'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-laut-100">
                            {{ $pengaturan['tentang_fitur3_teks'] }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Struktur Staf: latar tulang --}}
        <section class="bg-tulang">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
                <div>
                    <h2 class="font-judul text-3xl font-bold tracking-tight text-laut-900 sm:text-4xl">{{ $pengaturan['struktur_judul'] }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $pengaturan['struktur_subjudul'] }}</p>
                </div>

                @php
                    $daftarInti = ($stafInti ?? collect());
                    $pemimpin = $daftarInti->first();
                    $pengurus = $daftarInti->skip(1);
                @endphp

                @if ($daftarInti->isNotEmpty() && $pemimpin)
                    <div class="mx-auto mt-8 max-w-xl rounded-3xl border border-laut-100 bg-white px-6 py-8 text-center shadow-soft-md">
                        <x-avatar :foto="$pemimpin->stafProfile?->foto_url" :name="$pemimpin->name" size="lg" class="mx-auto" />
                        <p class="mt-4 font-judul text-4xl font-bold leading-tight sm:text-5xl">{{ $pemimpin->stafProfile?->jabatan ?? '-' }}</p>
                        <p class="mt-3 text-lg font-semibold text-arang">{{ $pemimpin->name }}</p>
                        <span class="mt-2 inline-flex items-center rounded-full bg-info-50 px-3 py-1 text-xs font-medium text-info-700">
                            {{ $pemimpin->stafProfile?->bidang?->nama ?? '-' }}
                        </span>
                        @if ($pemimpin->stafProfile?->kelas)
                            <p class="mt-2 text-sm text-gray-500">Kelas {{ $pemimpin->stafProfile?->kelas }}</p>
                        @endif
                    </div>

                    @if ($pengurus->isNotEmpty())
                        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($pengurus as $user)
                                <div class="rounded-2xl border border-laut-100 bg-white p-5 shadow-soft-sm">
                                    <div class="flex items-start gap-3">
                                        <x-avatar :foto="$user->stafProfile?->foto_url" :name="$user->name" size="md" />
                                        <div class="min-w-0">
                                            <p class="font-judul text-xl font-bold leading-tight text-laut-700">{{ $user->stafProfile?->jabatan ?? '-' }}</p>
                                            <p class="mt-1 text-sm font-semibold text-arang">{{ $user->name }}</p>
                                            <span class="mt-1.5 inline-flex items-center rounded-full bg-info-50 px-2.5 py-0.5 text-xs font-medium text-info-700">
                                                {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                                            </span>
                                            @if ($user->stafProfile?->kelas)
                                                <p class="mt-1 text-xs text-gray-500">Kelas {{ $user->stafProfile?->kelas }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <x-empty title="Belum ada data staf inti" ikon="staf" class="mt-8" />
                @endif
            </div>
        </section>

        {{-- Bidang: latar laut-50 --}}
        <section class="bg-laut-50">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
                <div>
                    <h2 class="font-judul text-3xl font-bold tracking-tight text-laut-900 sm:text-4xl">{{ $pengaturan['bidang_judul'] }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $pengaturan['bidang_subjudul'] }}</p>
                </div>

                @php
                    $tint = [
                        ['bg-info-50', 'text-info-600'],
                        ['bg-emas-50', 'text-emas-600'],
                        ['bg-sukses-50', 'text-sukses-600'],
                        ['bg-laut-100', 'text-laut-600'],
                    ];
                @endphp

                @if (($bidang ?? collect())->isNotEmpty())
                    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($bidang as $item)
                            <div class="flex items-center gap-3 rounded-2xl border border-laut-100 bg-white px-5 py-4 shadow-soft-sm">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $tint[$loop->index % 4][0] }}">
                                    <x-icon name="bidang" class="h-5 w-5 {{ $tint[$loop->index % 4][1] }}" />
                                </span>
                                <span class="text-base font-medium text-arang">{{ $item->nama }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty title="Belum ada data bidang" ikon="bidang" class="mt-8" />
                @endif
            </div>
        </section>

        {{-- Pita ajakan: latar emas-50 --}}
        <section class="bg-emas-50">
            <div class="mx-auto flex max-w-6xl flex-col items-start gap-5 px-4 py-12 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-14">
                <div class="max-w-2xl">
                    <h2 class="font-judul text-2xl font-bold text-arang sm:text-3xl">{{ $pengaturan['ajakan_judul'] }}</h2>
                    <p class="mt-2 text-base leading-relaxed text-arang/80">
                        {{ $pengaturan['ajakan_teks'] }}
                    </p>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-utama shrink-0">Buka Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-utama shrink-0">Daftar</a>
                @endauth
            </div>
        </section>

        {{-- Footer --}}
        <footer class="bg-laut-900 text-white">
            <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white p-0.5">
                            <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-9 w-9 rounded-full object-cover">
                        </span>
                        <div>
                            <p class="font-judul font-semibold">{{ $pengaturan['footer_nama'] }}</p>
                            <p class="text-sm text-laut-100">{{ $pengaturan['footer_sekolah'] }}</p>
                        </div>
                    </div>

                    <div class="w-full sm:w-72">
                        <div class="relative overflow-hidden rounded-xl border border-white/10 bg-laut-800">
                            <iframe
                                src="https://maps.google.com/maps?q={{ urlencode($pengaturan['footer_peta_kata_kunci']) }}&amp;z=16&amp;output=embed"
                                class="pointer-events-none h-40 w-full"
                                loading="lazy"
                                title="Peta lokasi {{ $pengaturan['footer_sekolah'] }}"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                            <a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($pengaturan['footer_peta_kata_kunci']) }}"
                                target="_blank" rel="noopener noreferrer"
                                aria-label="Buka lokasi {{ $pengaturan['footer_sekolah'] }} di Google Maps pada tab baru"
                                class="absolute inset-0 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-laut-900"></a>
                        </div>
                        <p class="mt-2 text-xs text-laut-100/70">{{ $pengaturan['footer_peta_catatan'] }}</p>
                    </div>
                </div>

                <p class="mt-8 border-t border-white/10 pt-6 text-sm text-laut-100">
                    {{ $pengaturan['footer_hak_cipta'] }} &middot; &copy; {{ date('Y') }}
                </p>
            </div>
        </footer>
    </body>
</html>
