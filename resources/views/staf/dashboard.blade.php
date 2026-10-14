<x-staf-layout title="Dashboard">
    @php
        $hariIni = now()->locale('id')->translatedFormat('l, j F Y');
        $jadwalTerdekat = $jadwalPiket->firstWhere('hariIni', false);
    @endphp

    {{-- Banner sambutan --}}
    <section class="relative overflow-hidden rounded-2xl bg-laut-700 px-5 py-6 text-white shadow-soft-md sm:px-7 sm:py-8">
        <div class="relative z-10 max-w-xl">
            <p class="font-judul text-xs font-semibold uppercase tracking-[0.18em] text-emas-300">Panel Staf</p>
            <h2 class="mt-1.5 font-judul text-2xl font-bold sm:text-3xl">Selamat datang, {{ auth()->user()->name }}</h2>
            <p class="mt-1.5 text-sm text-laut-100">
                {{ $profil?->bidang?->nama ?? 'Belum ada bidang' }}
                @if ($profil?->kelas)
                    &middot;
                    {{ $profil->kelas }}
                @endif
                &middot;
                {{ $profil?->jabatan ?? 'Anggota' }}
                &middot;
                {{ $hariIni }}
            </p>
        </div>

        <svg class="pointer-events-none absolute -right-6 -top-8 h-56 w-56 text-white/10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
        </svg>
        <span class="pointer-events-none absolute inset-x-0 bottom-0 h-1 bg-emas-500"></span>
    </section>

    <div id="piket" class="mt-4 scroll-mt-20 grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Giliran piket saya --}}
        <section aria-label="Jadwal Piket Saya">
            @if ($piketHariIni)
                <div class="relative overflow-hidden rounded-2xl bg-laut-700 p-5 text-white shadow-soft-md sm:p-6">
                    <p class="font-judul text-xs font-semibold uppercase tracking-[0.18em] text-emas-300">Jadwal Piket Saya</p>
                    <p class="mt-2 font-judul text-2xl font-bold text-white">Hari ini giliran Anda</p>
                    <p class="mt-1 text-sm text-laut-100">{{ $piketHariIni['tanggal']->translatedFormat('l, j F Y') }}</p>
                    @if ($piketHariIni['catatan'])
                        <p class="mt-3 rounded-xl bg-white/10 px-3 py-2 text-sm text-white">{{ $piketHariIni['catatan'] }}</p>
                    @endif
                    <span class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 text-emas-300/20" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    </span>
                </div>
            @else
                <x-card title="Jadwal Piket Saya">
                    @if ($jadwalTerdekat)
                        <div class="flex items-start gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-laut-50 text-laut-700">
                                <x-icon name="kalender" />
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-arang">Tidak ada giliran hari ini</p>
                                <p class="mt-0.5 text-sm text-gray-600">
                                    Jadwal terdekat: {{ $jadwalTerdekat['tanggal']->translatedFormat('l, j F Y') }}
                                </p>
                                @if ($jadwalTerdekat['catatan'])
                                    <p class="mt-1 text-xs leading-relaxed text-gray-500">{{ $jadwalTerdekat['catatan'] }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <x-empty title="Belum ada jadwal piket untuk Anda." ikon="piket">
                            Jadwal baru akan muncul di sini setelah admin membaginya.
                        </x-empty>
                    @endif
                </x-card>
            @endif
        </section>

        {{-- Kegiatan terdekat --}}
        <x-card title="Kegiatan Terdekat">
            @if ($kegiatan->isNotEmpty())
                <ul class="divide-y divide-laut-50">
                    @foreach ($kegiatan as $item)
                        <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-arang">{{ $item['nama'] }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">
                                    @if ($item['tanggalMulai'] || $item['tanggalSelesai'])
                                        {{ $item['tanggalMulai']?->translatedFormat('j F Y') ?? '-' }}
                                        &ndash;
                                        {{ $item['tanggalSelesai']?->translatedFormat('j F Y') ?? '-' }}
                                    @else
                                        Tahun {{ $item['tahun'] }}
                                    @endif
                                </p>
                            </div>
                            <x-badge :status="$item['status'] ?? 'rencana'" :label="$item['statusLabel']" />
                        </li>
                    @endforeach
                </ul>
            @else
                <x-empty title="Belum ada kegiatan" ikon="event">
                    Kegiatan baru akan muncul di sini setelah admin mencatatnya.
                </x-empty>
            @endif
        </x-card>
    </div>

    {{-- Jadwal minggu berikutnya --}}
    <x-card title="Jadwal Minggu Berikutnya" class="mt-4">
        @if ($jadwalPiket->isNotEmpty())
            <ul class="divide-y divide-laut-50">
                @foreach ($jadwalPiket as $item)
                    <li class="flex flex-wrap items-center justify-between gap-2 py-3 first:pt-0 last:pb-0">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-arang">{{ $item['tanggal']->translatedFormat('l, j F Y') }}</p>
                            @if ($item['catatan'])
                                <p class="mt-0.5 text-xs leading-relaxed text-gray-500">{{ $item['catatan'] }}</p>
                            @endif
                        </div>
                        <x-badge :status="$item['mingguIni'] ? 'disetujui' : 'rencana'"
                                 :label="$item['mingguIni'] ? 'Minggu ini' : 'Minggu depan'" />
                    </li>
                @endforeach
            </ul>
        @else
            <x-empty title="Belum ada jadwal piket" ikon="kalender">
                Jadwal baru akan muncul di sini setelah admin membaginya.
            </x-empty>
        @endif
    </x-card>
</x-staf-layout>
