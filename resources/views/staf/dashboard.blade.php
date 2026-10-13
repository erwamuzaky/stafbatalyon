<x-staf-layout title="Dashboard">
    {{-- Sapaan --}}
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <h2 class="text-base font-semibold text-gray-800">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="mt-1 text-sm text-gray-600">
            Bidang: {{ $profil?->bidang?->nama ?? '-' }}
            &middot;
            Jabatan: {{ $profil?->jabatan ?? '-' }}
        </p>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        {{-- Jadwal piket --}}
        <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-gray-800">Jadwal Piket Saya</h2>

            @if ($piketHariIni)
                <div class="mt-3 rounded-md border border-indigo-300 bg-indigo-50 px-4 py-3 text-sm font-semibold text-indigo-800">
                    Hari ini giliran Anda
                    <span class="mt-0.5 block text-xs font-normal text-indigo-600">
                        {{ $piketHariIni['tanggal']->translatedFormat('l, j F Y') }}
                    </span>
                </div>
            @endif

            @if ($jadwalPiket->isNotEmpty())
                <ul class="mt-3 space-y-2">
                    @foreach ($jadwalPiket as $item)
                        <li class="flex flex-wrap items-center justify-between gap-2 rounded-md border px-3 py-2 {{ $item['hariIni'] ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200' }}">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item['tanggal']->translatedFormat('l, j F Y') }}</p>
                                @if ($item['catatan'])
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $item['catatan'] }}</p>
                                @endif
                            </div>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $item['mingguIni'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item['mingguIni'] ? 'Minggu ini' : 'Minggu depan' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-3 text-sm text-gray-500">Belum ada jadwal piket untuk Anda.</p>
            @endif
        </div>

        {{-- Kegiatan terdekat --}}
        <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
            <h2 class="text-base font-semibold text-gray-800">Kegiatan Terdekat</h2>

            @if ($kegiatan->isNotEmpty())
                <ul class="mt-3 space-y-2">
                    @foreach ($kegiatan as $item)
                        @php
                            $badge = ($item['status'] ?? null) === 'berjalan'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-indigo-100 text-indigo-800';
                        @endphp
                        <li class="rounded-md border border-gray-200 px-3 py-2">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-medium text-gray-800">{{ $item['nama'] }}</p>
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">
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
                <p class="mt-3 text-sm text-gray-500">Belum ada kegiatan yang direncanakan.</p>
            @endif
        </div>
    </div>
</x-staf-layout>
