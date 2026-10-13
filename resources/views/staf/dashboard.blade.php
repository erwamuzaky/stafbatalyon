<x-staf-layout title="Dashboard">
    {{-- Sapaan --}}
    <div class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-judul text-xl font-semibold text-laut-800">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="mt-1 text-sm text-gray-600">
            Bidang: <span class="font-medium text-arang">{{ $profil?->bidang?->nama ?? '-' }}</span>
            &middot;
            Jabatan: <span class="font-medium text-arang">{{ $profil?->jabatan ?? '-' }}</span>
        </p>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
        {{-- Jadwal piket --}}
        <section aria-label="Jadwal piket saya" class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Jadwal Piket Saya</h2>

            @if ($piketHariIni)
                <div class="mt-4 rounded-lg border border-emas-500 border-l-4 bg-laut-50 px-4 py-3">
                    <p class="text-sm font-semibold text-laut-800">Hari ini giliran Anda</p>
                    <p class="mt-0.5 text-xs text-laut-700">
                        {{ $piketHariIni['tanggal']->translatedFormat('l, j F Y') }}
                    </p>
                    @if ($piketHariIni['catatan'])
                        <p class="mt-1 text-xs leading-relaxed text-gray-600">{{ $piketHariIni['catatan'] }}</p>
                    @endif
                </div>
            @endif

            @if ($jadwalPiket->isNotEmpty())
                <ul class="mt-4 space-y-2">
                    @foreach ($jadwalPiket as $item)
                        <li class="rounded-lg border px-3 py-2.5 transition-colors {{ $item['hariIni'] ? 'border-emas-500 border-l-4 bg-laut-50' : 'border-gray-200 hover:bg-gray-50/60' }}">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-medium text-arang">{{ $item['tanggal']->translatedFormat('l, j F Y') }}</p>
                                <span class="badge {{ $item['mingguIni'] ? 'badge-disetujui' : 'badge-selesai' }}">
                                    {{ $item['mingguIni'] ? 'Minggu ini' : 'Minggu depan' }}
                                </span>
                            </div>
                            @if ($item['catatan'])
                                <p class="mt-1 text-xs leading-relaxed text-gray-500">{{ $item['catatan'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="mt-4 rounded-lg border border-dashed border-laut-200 bg-laut-50/50 px-4 py-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada jadwal piket untuk Anda.</p>
                    <p class="mt-1 text-xs text-gray-400">Jadwal baru akan muncul di sini setelah admin membaginya.</p>
                </div>
            @endif
        </section>

        {{-- Kegiatan terdekat --}}
        <section aria-label="Kegiatan terdekat" class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Kegiatan Terdekat</h2>

            @if ($kegiatan->isNotEmpty())
                <ul class="mt-4 space-y-2">
                    @foreach ($kegiatan as $item)
                        @php
                            $badge = match ($item['status'] ?? null) {
                                'berjalan' => 'badge-berjalan',
                                'rencana' => 'badge-rencana',
                                'selesai' => 'badge-selesai',
                                default => 'badge-selesai',
                            };
                        @endphp
                        <li class="rounded-lg border border-gray-200 px-3 py-2.5">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-medium text-arang">{{ $item['nama'] }}</p>
                                <span class="badge {{ $badge }}">
                                    {{ $item['statusLabel'] }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                @if ($item['tanggalMulai'] || $item['tanggalSelesai'])
                                    {{ $item['tanggalMulai']?->translatedFormat('j F Y') ?? '-' }}
                                    &ndash;
                                    {{ $item['tanggalSelesai']?->translatedFormat('j F Y') ?? '-' }}
                                @else
                                    Tahun {{ $item['tahun'] }}
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="mt-4 rounded-lg border border-dashed border-laut-200 bg-laut-50/50 px-4 py-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada kegiatan yang direncanakan.</p>
                    <p class="mt-1 text-xs text-gray-400">Kegiatan baru akan muncul di sini setelah admin mencatatnya.</p>
                </div>
            @endif
        </section>
    </div>
</x-staf-layout>
