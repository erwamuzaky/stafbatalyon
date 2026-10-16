<x-admin-layout title="Piket Gerbang">
    <x-card>
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <h2 class="font-judul text-lg font-semibold text-laut-800">Jadwal Piket Gerbang</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Minggu {{ $hari[0]['label'] }} {{ $hari[0]['tanggalLabel'] }}
                    &ndash; {{ $hari[count($hari) - 1]['label'] }} {{ $hari[count($hari) - 1]['tanggalLabel'] }}
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    Setiap hari hanya ada satu jadwal piket gerbang. Bisa diisi beberapa staf sekaligus.
                </p>
            </div>

            <nav aria-label="Pindah minggu" class="grid w-full grid-cols-1 gap-2 sm:w-auto sm:grid-cols-3">
                <a href="{{ route('admin.piket.index', ['minggu' => $mingguSebelumnya]) }}"
                   class="btn btn-sekunder text-xs">
                    Minggu Sebelumnya
                </a>

                <a href="{{ route('admin.piket.index') }}"
                   class="btn text-xs {{ $minggu->toDateString() === $mingguIni ? 'border border-emas-500 bg-emas-50 text-arang hover:bg-emas-100/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500 focus-visible:ring-offset-2 focus-visible:ring-offset-tulang' : 'btn-sekunder' }}">
                    Minggu Ini
                </a>

                <a href="{{ route('admin.piket.index', ['minggu' => $mingguBerikutnya]) }}"
                   class="btn btn-sekunder text-xs">
                    Minggu Berikutnya
                </a>
            </nav>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4">
            <a href="{{ route('admin.piket.create') }}" class="btn btn-utama">
                <x-icon name="tambah" class="h-4 w-4" />
                Tambah piket
            </a>

            <a href="{{ route('admin.piket.rekap') }}" class="btn btn-sekunder">
                <x-icon name="dokumen" class="h-4 w-4" />
                Rekap &amp; pemerataan
            </a>

            <form method="POST" action="{{ route('admin.piket.salin') }}"
                  onsubmit="return confirm('Salin jadwal minggu ini ke minggu berikutnya?')">
                @csrf
                <input type="hidden" name="minggu" value="{{ $minggu->toDateString() }}">
                <button type="submit" class="btn btn-sekunder">Salin ke minggu depan</button>
            </form>
        </div>
    </x-card>

    <x-tabel-responsif :headers="array_merge(['Hari'], array_values($sesi))" class="mt-4">
        @foreach ($hari as $item)
            @php $apakahHariIni = $item['tanggal']->isToday(); @endphp
            <tr class="{{ $apakahHariIni ? 'bg-laut-50' : '' }} transition-colors hover:bg-laut-50/60">
                <td class="relative w-44 px-4 py-3 align-top">
                    @if ($apakahHariIni)
                        <span class="absolute inset-y-0 left-0 w-1 bg-emas-500"></span>
                    @endif
                    <div class="flex flex-wrap items-center gap-2 font-medium text-arang">
                        {{ $item['label'] }}
                        @if ($apakahHariIni)
                            <x-badge status="menunggu" label="Hari ini" />
                        @endif
                    </div>
                    <span class="mt-0.5 block text-xs text-gray-500">{{ $item['tanggalLabel'] }}</span>
                </td>

                @foreach ($sesi as $kode => $label)
                    <td class="px-4 py-3 align-top">
                        @php $entri = $jadwal[$item['tanggal']->toDateString()][$kode] ?? []; @endphp

                        @forelse ($entri as $baris)
                            <div class="mb-2 flex items-center justify-between gap-2 rounded-lg border border-laut-100 bg-white px-2.5 py-1.5 shadow-soft-sm last:mb-0">
                                <span class="inline-flex min-w-0 items-center gap-2">
                                    <x-avatar :name="$baris->user?->name ?? '?'" size="sm" />
                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-medium text-arang">{{ $baris->user?->name ?? 'Staf terhapus' }}</span>
                                        @if ($baris->catatan)
                                            <span class="block truncate text-xs text-gray-500">{{ $baris->catatan }}</span>
                                        @endif
                                    </span>
                                </span>
                                <span class="flex shrink-0 items-center gap-1">
                                    <x-aksi :href="route('admin.piket.edit', $baris)" ikon="ubah" label="Ubah piket" />
                                    <x-konfirmasi-hapus :action="route('admin.piket.destroy', $baris)"
                                                        :judul="'piket '.($baris->user?->name ?? 'staf')"
                                                        teks="Hapus piket" ikon="hapus" />
                                </span>
                            </div>
                        @empty
                            <span class="text-xs text-gray-400">Belum ada</span>
                        @endforelse
                    </td>
                @endforeach
            </tr>
        @endforeach

        <x-slot name="mobile">
            @foreach ($hari as $item)
                @php $apakahHariIniHp = $item['tanggal']->isToday(); @endphp
                <section aria-label="Piket {{ $item['label'] }}"
                         class="relative overflow-hidden rounded-xl border bg-white p-4 {{ $apakahHariIniHp ? 'border-emas-300 bg-laut-50' : 'border-laut-100' }}">
                    @if ($apakahHariIniHp)
                        <span class="absolute inset-y-0 left-0 w-1 bg-emas-500"></span>
                    @endif
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h3 class="font-judul text-base font-semibold text-laut-800">{{ $item['label'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $item['tanggalLabel'] }}</p>
                        </div>
                        @if ($apakahHariIniHp)
                            <x-badge status="menunggu" label="Hari ini" />
                        @endif
                    </div>

                    @foreach ($sesi as $kode => $label)
                        <p class="mt-3 text-xs font-medium text-gray-500">{{ $label }}</p>
                        @php $entriHp = $jadwal[$item['tanggal']->toDateString()][$kode] ?? []; @endphp
                        @forelse ($entriHp as $baris)
                            <div class="mt-2 flex items-center justify-between gap-2 rounded-lg border border-laut-100 bg-white px-3 py-2">
                                <span class="inline-flex min-w-0 items-center gap-2">
                                    <x-avatar :name="$baris->user?->name ?? '?'" size="sm" />
                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-medium text-arang">{{ $baris->user?->name ?? 'Staf terhapus' }}</span>
                                        @if ($baris->catatan)
                                            <span class="block truncate text-xs text-gray-500">{{ $baris->catatan }}</span>
                                        @endif
                                    </span>
                                </span>
                                <span class="flex shrink-0 items-center gap-1">
                                    <x-aksi :href="route('admin.piket.edit', $baris)" ikon="ubah" label="Ubah piket" />
                                    <x-konfirmasi-hapus :action="route('admin.piket.destroy', $baris)"
                                                        :judul="'piket '.($baris->user?->name ?? 'staf')"
                                                        teks="Hapus piket" ikon="hapus" />
                                </span>
                            </div>
                        @empty
                            <p class="mt-2 text-xs text-gray-400">Belum ada</p>
                        @endforelse
                    @endforeach
                </section>
            @endforeach
        </x-slot>
    </x-tabel-responsif>
</x-admin-layout>
