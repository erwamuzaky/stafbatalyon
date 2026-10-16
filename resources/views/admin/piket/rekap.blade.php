<x-admin-layout title="Rekap Piket">
    <x-card>
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Rekap &amp; Pemerataan Piket Gerbang</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Periksa apakah pembagian piket sudah merata antar staf pada rentang tanggal tertentu.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.piket.rekap.cetak', request()->query()) }}"
                       target="_blank" rel="noopener" class="btn btn-sekunder">
                        <x-icon name="cetak" class="h-4 w-4" />
                        Cetak PDF
                    </a>
                    <a href="{{ route('admin.piket.index') }}" class="btn btn-sekunder">
                        <x-icon name="piket" class="h-4 w-4" />
                        Jadwal mingguan
                    </a>
                </div>
            </div>
        </x-slot>

        {{-- Penyaring rentang tanggal dan bidang --}}
        <form method="GET" action="{{ route('admin.piket.rekap') }}" class="flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="dari" value="Dari tanggal" />
                <x-text-input id="dari" name="dari" type="date" class="mt-1 block w-full sm:w-44"
                              :value="$dari->toDateString()" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="sampai" value="Sampai tanggal" />
                <x-text-input id="sampai" name="sampai" type="date" class="mt-1 block w-full sm:w-44"
                              :value="$sampai->toDateString()" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="bidang_id" value="Bidang" />
                <select id="bidang_id" name="bidang_id" class="input-baku mt-1 block w-full sm:w-48">
                    <option value="">Semua bidang</option>
                    @foreach ($bidangPilihan as $bidang)
                        <option value="{{ $bidang->id }}" @selected($bidangId === $bidang->id)>
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>
                    <x-icon name="cari" class="h-4 w-4" />
                    Tampilkan
                </x-primary-button>

                <a href="{{ route('admin.piket.rekap') }}"
                   class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
                    Reset
                </a>
            </div>

            <div class="w-full space-y-1">
                <x-input-error :messages="$errors->get('dari')" />
                <x-input-error :messages="$errors->get('sampai')" />
                <x-input-error :messages="$errors->get('bidang_id')" />
            </div>
        </form>

        <p class="mt-4 text-sm text-gray-500">
            Rentang:
            <span class="font-medium text-arang">{{ $dari->translatedFormat('j F Y') }}</span>
            &ndash;
            <span class="font-medium text-arang">{{ $sampai->translatedFormat('j F Y') }}</span>
            @if ($namaBidang)
                &middot; Bidang {{ $namaBidang }}
            @endif
        </p>
    </x-card>

    {{-- Angka ringkasan --}}
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        <x-stat label="Total Slot Piket" :value="$ringkasan['total']" variant="laut" ikon="piket"
                deskripsi="Jumlah jadwal piket pada rentang ini." />

        <x-stat label="Staf Dapat Piket" :value="$ringkasan['jumlahStaf']" variant="info" ikon="staf"
                deskripsi="Staf yang tercatat mendapat piket." />

        <x-stat label="Rata-rata per Staf"
                :value="number_format($ringkasan['rataRata'], 1, ',', '.')"
                variant="sukses" ikon="staf"
                deskripsi="Dihitung dari staf yang dapat piket." />

        <x-stat label="Piket Terbanyak" :value="$ringkasan['terbanyak']" variant="peringatan" ikon="bintang"
                :deskripsi="$ringkasan['namaTerbanyak'] === [] ? null : implode(', ', $ringkasan['namaTerbanyak'])" />

        <x-stat label="Piket Paling Sedikit" :value="$ringkasan['tersedikit']" variant="bahaya" ikon="bintang"
                :deskripsi="$ringkasan['namaTersedikit'] === [] ? null : implode(', ', $ringkasan['namaTersedikit'])" />
    </div>

    {{-- Tabel per staf --}}
    @php
        $headers = array_merge(['Staf', 'Bidang', 'Total'], array_values($sesi), ['Tanggal piket']);
    @endphp

    <x-tabel-responsif :headers="$headers" class="mt-4">
        @forelse ($tabel as $baris)
            <tr class="transition-colors hover:bg-laut-50/50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="$baris['user']->name" :foto="$baris['user']->stafProfile?->foto_url" size="sm" />
                        <div class="min-w-0">
                            <p class="truncate font-medium text-arang">{{ $baris['user']->name }}</p>
                            <div class="mt-0.5 flex flex-wrap items-center gap-1">
                                @if ($baris['terbanyak'])
                                    <x-badge status="sukses" label="Terbanyak" />
                                @endif
                                @if ($baris['tersedikit'])
                                    <x-badge status="peringatan" label="Paling sedikit" />
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $baris['user']->stafProfile?->bidang?->nama ?? '-' }}</td>
                <td class="px-4 py-3 font-semibold text-arang">{{ $baris['total'] }}</td>

                @foreach ($sesi as $kode => $label)
                    <td class="px-4 py-3 text-gray-700">{{ $baris['perSesi'][$kode] ?? 0 }}</td>
                @endforeach

                <td class="px-4 py-3 text-gray-700">
                    {{ implode(', ', array_map(fn ($tgl) => $tgl->translatedFormat('d/m/Y'), array_slice($baris['tanggal'], 0, $batasTanggal))) }}
                    @if (count($baris['tanggal']) > $batasTanggal)
                        <span class="mt-0.5 block text-xs text-gray-500">
                            +{{ count($baris['tanggal']) - $batasTanggal }} tanggal lain
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($sesi) + 4 }}" class="px-4 py-10">
                    <x-empty title="Belum ada piket pada rentang ini" ikon="piket">
                        Ubah rentang tanggal, atau tambah jadwal piket lewat halaman Piket Gerbang.
                    </x-empty>
                </td>
            </tr>
        @endforelse

        <x-slot name="mobile">
            @forelse ($tabel as $baris)
                <div class="rounded-xl border border-laut-100 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-avatar :name="$baris['user']->name" :foto="$baris['user']->stafProfile?->foto_url" size="md" />
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-arang">{{ $baris['user']->name }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $baris['user']->stafProfile?->bidang?->nama ?? 'Tanpa bidang' }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 font-judul text-lg font-semibold text-arang">{{ $baris['total'] }}</span>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-1">
                        @if ($baris['terbanyak'])
                            <x-badge status="sukses" label="Terbanyak" />
                        @endif
                        @if ($baris['tersedikit'])
                            <x-badge status="peringatan" label="Paling sedikit" />
                        @endif
                    </div>

                    <dl class="mt-3 space-y-1 text-xs text-gray-600">
                        @foreach ($sesi as $kode => $label)
                            <div class="flex justify-between gap-2">
                                <dt>{{ $label }}</dt>
                                <dd class="font-medium text-arang">{{ $baris['perSesi'][$kode] ?? 0 }}</dd>
                            </div>
                        @endforeach
                        <div class="flex justify-between gap-2">
                            <dt>Tanggal piket</dt>
                            <dd class="max-w-[60%] text-right font-medium text-arang">
                                {{ implode(', ', array_map(fn ($tgl) => $tgl->translatedFormat('d/m/Y'), array_slice($baris['tanggal'], 0, $batasTanggal))) }}
                                @if (count($baris['tanggal']) > $batasTanggal)
                                    <span class="block text-gray-500">+{{ count($baris['tanggal']) - $batasTanggal }} tanggal lain</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            @empty
                <x-empty title="Belum ada piket pada rentang ini" ikon="piket">
                    Ubah rentang tanggal, atau tambah jadwal piket lewat halaman Piket Gerbang.
                </x-empty>
            @endforelse
        </x-slot>
    </x-tabel-responsif>

    {{-- Hal yang perlu diperhatikan --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <x-card title="Piket Hari Berurutan">
            @if ($berurutan !== [])
                <ul class="divide-y divide-laut-50">
                    @foreach ($berurutan as $item)
                        <li class="flex flex-wrap items-center justify-between gap-2 py-3 first:pt-0 last:pb-0">
                            <span class="min-w-0 truncate text-sm font-medium text-arang">{{ $item['user']->name }}</span>
                            <span class="flex flex-wrap items-center gap-1">
                                @foreach ($item['rentang'] as $rentang)
                                    <x-badge status="menunggu"
                                             :label="$rentang['dari']->translatedFormat('d/m').' – '.$rentang['sampai']->translatedFormat('d/m/Y').' ('.$rentang['jumlah'].' hari)'" />
                                @endforeach
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <x-empty title="Tidak ada piket berurutan" ikon="kalender">
                    Tidak ada staf yang mendapat piket pada hari-hari berurutan pada rentang ini.
                </x-empty>
            @endif
        </x-card>

        <x-card title="Belum Dapat Piket">
            @if ($belum !== [])
                <p class="text-sm text-gray-600">
                    Staf berikut sudah disetujui tetapi belum mendapat piket pada rentang ini:
                </p>
                <ul class="mt-3 flex flex-wrap gap-2">
                    @foreach ($belum as $staf)
                        <li class="inline-flex items-center gap-2 rounded-full border border-peringatan-200 bg-peringatan-50 py-1 pl-1 pr-3">
                            <x-avatar :name="$staf->name" :foto="$staf->stafProfile?->foto_url" size="sm" />
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-medium text-arang">{{ $staf->name }}</span>
                                <span class="block truncate text-xs text-gray-500">{{ $staf->stafProfile?->bidang?->nama ?? 'Tanpa bidang' }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <x-empty title="Semua sudah kebagian" ikon="setuju">
                    Semua staf yang dapat piket sudah mendapat jadwal pada rentang ini.
                </x-empty>
            @endif
        </x-card>
    </div>
</x-admin-layout>
