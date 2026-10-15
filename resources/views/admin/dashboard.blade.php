<x-admin-layout title="Dashboard">
    @php
        $hariIni = now()->locale('id')->translatedFormat('l, j F Y');
    @endphp

    {{-- Banner sambutan --}}
    <section class="relative overflow-hidden rounded-2xl bg-laut-700 px-5 py-6 text-white shadow-soft-md sm:px-7 sm:py-8">
        <div class="relative z-10 max-w-xl">
            <p class="font-judul text-xs font-semibold uppercase tracking-[0.18em] text-emas-300">Panel Admin</p>
            <h2 class="mt-1.5 font-judul text-2xl font-bold sm:text-3xl">Selamat datang, {{ auth()->user()->name }}</h2>
            <p class="mt-1.5 text-sm text-laut-100">{{ $hariIni }}</p>
        </div>

        {{-- Pola bintang halus + aksen emas --}}
        <svg class="pointer-events-none absolute -right-6 -top-8 h-56 w-56 text-white/10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
        </svg>
        <svg class="pointer-events-none absolute -right-16 bottom-[-3rem] h-40 w-40 text-emas-300/25" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
        </svg>
        <span class="pointer-events-none absolute inset-x-0 bottom-0 h-1 bg-emas-500"></span>
    </section>

    {{-- Angka ringkasan --}}
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat label="Staf Aktif" :value="$jumlahStaf" variant="sukses" ikon="staf"
                deskripsi="Staf biasa yang sudah disetujui admin." />

        <x-stat label="Menunggu Persetujuan" :value="$jumlahPending" variant="peringatan" ikon="akun"
                :menonjol="true"
                :href="route('admin.akun.index', ['status' => 'pending'])"
                deskripsi="Buka Kelola Akun untuk memeriksa pendaftar." />

        <x-stat label="Kegiatan" :value="$jumlahEvent" variant="info" ikon="event"
                :href="route('admin.event.index')"
                deskripsi="Lihat daftar kegiatan." />

        <x-stat label="Piket Minggu Ini" :value="$jumlahPiketMingguIni" variant="laut" ikon="piket"
                :href="route('admin.piket.index')"
                deskripsi="Atur pembagian piket gerbang." />
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        {{-- Pendaftar terbaru --}}
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Pendaftar Menunggu</h3>
                    <a href="{{ route('admin.akun.index', ['status' => 'pending']) }}"
                       class="text-xs font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">
                        Lihat semua
                    </a>
                </div>
            </x-slot>

            @if (($pendaftarTerbaru ?? collect())->isNotEmpty())
                <ul class="divide-y divide-laut-50">
                    @foreach ($pendaftarTerbaru as $item)
                        <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="flex min-w-0 items-center gap-3">
                                <x-avatar :name="$item->name" size="md" :foto="$item->stafProfile?->foto_url" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-arang">{{ $item->name }}</p>
                                    <p class="truncate text-xs text-gray-500">{{ $item->stafProfile?->bidang?->nama ?? $item->email }}</p>
                                </div>
                            </div>
                            <x-badge status="menunggu" />
                        </li>
                    @endforeach
                </ul>
            @else
                <x-empty title="Belum ada pendaftar" ikon="akun">
                    Pendaftar baru akan tampil di sini untuk disetujui atau ditolak.
                </x-empty>
            @endif
        </x-card>

        {{-- Piket hari ini --}}
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Piket Hari Ini</h3>
                    <a href="{{ route('admin.piket.index') }}"
                       class="text-xs font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">
                        Kelola
                    </a>
                </div>
            </x-slot>

            @if (($piketHariIni ?? collect())->isNotEmpty())
                <ul class="divide-y divide-laut-50">
                    @foreach ($piketHariIni as $p)
                        <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="flex min-w-0 items-center gap-3">
                                <x-avatar :name="$p->user?->name ?? '-'" size="md" />
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-arang">{{ $p->user?->name ?? '-' }}</p>
                                    <p class="truncate text-xs text-gray-500">Sesi: {{ $p->sesi ?? '-' }}</p>
                                </div>
                            </div>
                            <x-badge status="berjalan" label="Ditugaskan" />
                        </li>
                    @endforeach
                </ul>
            @else
                <x-empty title="Belum ada piket hari ini" ikon="piket">
                    Tambahkan pembagian piket lewat halaman Piket Gerbang.
                </x-empty>
            @endif
        </x-card>
    </div>

    {{-- Sebaran kelas staf aktif --}}
    <x-card class="mt-4">
        <x-slot name="header">
            <div class="flex items-center justify-between gap-3">
                <h3 class="font-judul text-lg font-semibold text-laut-800">Sebaran Kelas</h3>
                <a href="{{ route('admin.staf.index') }}"
                   class="text-xs font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">
                    Data Staf
                </a>
            </div>
        </x-slot>

        @if (($sebaranKelas ?? collect())->isNotEmpty())
            <ul class="flex flex-wrap gap-2">
                @foreach ($sebaranKelas as $namaKelas => $jumlah)
                    <li>
                        <a href="{{ route('admin.staf.index', ['kelas' => $namaKelas]) }}"
                           title="Lihat staf kelas {{ $namaKelas }}"
                           class="inline-flex items-center gap-2 rounded-full border border-laut-100 bg-laut-50 py-1.5 pl-3 pr-1.5 transition-colors hover:border-laut-200 hover:bg-laut-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
                            <span class="text-sm font-medium text-arang">{{ $namaKelas }}</span>
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-semibold text-laut-700">{{ $jumlah }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <x-empty title="Belum ada data kelas" ikon="staf">
                Kelas staf aktif akan tampil di sini setelah diisi.
            </x-empty>
        @endif
    </x-card>
</x-admin-layout>
