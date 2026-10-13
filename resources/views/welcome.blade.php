<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Staf Batalyon - SMKN 3 Pariaman</title>

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
                        <span class="block truncate font-judul text-base font-bold text-arang">Staf Batalyon</span>
                        <span class="hidden text-xs text-arang/70 sm:block">SMKN 3 Pariaman</span>
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
        <header class="animasi-muat bg-tulang">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 pb-14 pt-10 sm:px-6 sm:pt-14 lg:grid-cols-[minmax(0,1fr)_minmax(0,440px)] lg:items-center lg:gap-12">
                <div>
                    <p class="font-judul text-sm font-semibold uppercase tracking-[0.18em] text-emas-600">Profil Organisasi</p>
                    <h1 class="mt-2 font-judul text-5xl font-bold leading-[0.95] tracking-tight text-arang sm:text-6xl lg:text-7xl">Staf Batalyon</h1>
                    <p class="mt-2 font-judul text-2xl font-semibold text-laut-600 sm:text-3xl">SMKN 3 Pariaman</p>
                    <p class="mt-5 max-w-xl text-base leading-relaxed text-arang/80">
                        Organisasi kesiswaan yang menjaga ketertiban harian sekolah dan
                        menjalankan kegiatan serta kepanitiaan sepanjang tahun ajaran.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-utama">Buka Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sekunder">Masuk</a>
                            <a href="{{ route('register') }}" class="btn btn-utama">Daftar</a>
                        @endauth
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-3xl bg-laut-700 p-6 text-white shadow-soft-lg sm:p-8">
                    <x-pola-bintang class="text-white/[0.07]" id="pola-hero" />

                    <div class="relative">
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-white p-1 shadow-soft-sm">
                            <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-12 w-12 rounded-full object-cover">
                        </span>
                        <p class="mt-5 font-judul text-2xl font-bold leading-tight">Staf Batalyon</p>
                        <p class="mt-1 text-sm text-laut-100">SMKN 3 Pariaman</p>
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

        {{-- Tentang: latar laut-900 penuh lebar --}}
        <section class="bg-laut-900 text-white">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
                <div class="max-w-2xl">
                    <p class="font-judul text-sm font-semibold uppercase tracking-[0.18em] text-emas-400">Tentang</p>
                    <h2 class="mt-2 font-judul text-3xl font-bold sm:text-4xl">Menjaga ketertiban, menjalankan kegiatan</h2>
                    <p class="mt-4 text-base leading-relaxed text-laut-100">
                        Staf Batalyon adalah organisasi kesiswaan SMKN 3 Pariaman yang membantu
                        sekolah menjaga ketertiban harian dan memastikan setiap kegiatan berjalan
                        tertib, dari persiapan sampai arsipnya tersimpan.
                    </p>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-3">
                    <div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-emas-400">
                            <x-icon name="piket" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-judul text-lg font-semibold">Pengamanan gerbang</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-laut-100">
                            Mengatur jadwal dan menjalankan piket gerbang harian bersama seluruh staf.
                        </p>
                    </div>
                    <div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-emas-400">
                            <x-icon name="event" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-judul text-lg font-semibold">Pelaksanaan kegiatan</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-laut-100">
                            Membantu kepanitiaan dan memastikan kegiatan sekolah berjalan lancar.
                        </p>
                    </div>
                    <div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-emas-400">
                            <x-icon name="dokumen" class="h-5 w-5" />
                        </span>
                        <h3 class="mt-4 font-judul text-lg font-semibold">Administrasi</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-laut-100">
                            Menyimpan arsip proposal dan merapikan data keanggotaan batalyon.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Struktur Staf: latar tulang --}}
        <section class="bg-tulang">
            <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
                <div>
                    <h2 class="font-judul text-3xl font-bold tracking-tight text-laut-900 sm:text-4xl">Struktur Staf</h2>
                    <p class="mt-1 text-sm text-gray-500">Staf inti yang memimpin kepengurusan periode berjalan.</p>
                </div>

                @php
                    $daftarInti = ($stafInti ?? collect());
                    $pemimpin = $daftarInti->first();
                    $pengurus = $daftarInti->skip(1);
                @endphp

                @if ($daftarInti->isNotEmpty() && $pemimpin)
                    <div class="mx-auto mt-8 max-w-xl rounded-3xl border border-laut-100 bg-white px-6 py-8 text-center shadow-soft-md">
                        <x-avatar :name="$pemimpin->name" size="lg" class="mx-auto" />
                        <p class="mt-4 font-judul text-4xl font-bold leading-tight sm:text-5xl">{{ $pemimpin->stafProfile?->jabatan ?? '-' }}</p>
                        <p class="mt-3 text-lg font-semibold text-arang">{{ $pemimpin->name }}</p>
                        <span class="mt-2 inline-flex items-center rounded-full bg-info-50 px-3 py-1 text-xs font-medium text-info-700">
                            {{ $pemimpin->stafProfile?->bidang?->nama ?? '-' }}
                        </span>
                    </div>

                    @if ($pengurus->isNotEmpty())
                        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($pengurus as $user)
                                <div class="rounded-2xl border border-laut-100 bg-white p-5 shadow-soft-sm">
                                    <div class="flex items-start gap-3">
                                        <x-avatar :name="$user->name" size="md" />
                                        <div class="min-w-0">
                                            <p class="font-judul text-xl font-bold leading-tight text-laut-700">{{ $user->stafProfile?->jabatan ?? '-' }}</p>
                                            <p class="mt-1 text-sm font-semibold text-arang">{{ $user->name }}</p>
                                            <span class="mt-1.5 inline-flex items-center rounded-full bg-info-50 px-2.5 py-0.5 text-xs font-medium text-info-700">
                                                {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                                            </span>
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
                    <h2 class="font-judul text-3xl font-bold tracking-tight text-laut-900 sm:text-4xl">Bidang</h2>
                    <p class="mt-1 text-sm text-gray-500">Bidang-bidang yang menaungi seluruh staf batalyon.</p>
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
                    <h2 class="font-judul text-2xl font-bold text-arang sm:text-3xl">Baru ditunjuk sebagai staf?</h2>
                    <p class="mt-2 text-base leading-relaxed text-arang/80">
                        Daftar akun staf dulu, lalu tunggu persetujuan admin sebelum bisa masuk.
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
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white p-0.5">
                            <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-9 w-9 rounded-full object-cover">
                        </span>
                        <div>
                            <p class="font-judul font-semibold">Staf Batalyon</p>
                            <p class="text-sm text-laut-100">SMKN 3 Pariaman</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-5 text-sm">
                        <a href="{{ route('login') }}" class="text-laut-100 transition-colors hover:text-white">Masuk</a>
                        <a href="{{ route('register') }}" class="text-laut-100 transition-colors hover:text-white">Daftar</a>
                    </div>
                </div>

                <p class="mt-8 border-t border-white/10 pt-6 text-sm text-laut-100">
                    Staf Batalyon SMKN 3 Pariaman &middot; &copy; {{ date('Y') }}
                </p>
            </div>
        </footer>
    </body>
</html>
