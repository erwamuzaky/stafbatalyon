<x-admin-layout title="Piket Gerbang">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <h2 class="font-judul text-lg font-semibold text-laut-800">Jadwal Piket Gerbang</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Minggu {{ $hari[0]['label'] }} {{ $hari[0]['tanggalLabel'] }}
                    &ndash; {{ $hari[count($hari) - 1]['label'] }} {{ $hari[count($hari) - 1]['tanggalLabel'] }}
                </p>
                <p class="mt-1 text-xs leading-relaxed text-gray-500">
                    Setiap hari hanya ada satu jadwal piket gerbang. Bisa diisi beberapa staf sekaligus.
                </p>
            </div>

            <nav aria-label="Pindah minggu" class="grid w-full grid-cols-1 gap-2 sm:w-auto sm:grid-cols-3">
                <a href="{{ route('admin.piket.index', ['minggu' => $mingguSebelumnya]) }}"
                   class="btn btn-sekunder px-3 py-2 text-xs">
                    Minggu Sebelumnya
                </a>

                <a href="{{ route('admin.piket.index') }}"
                   class="btn px-3 py-2 text-xs {{ $minggu->toDateString() === $mingguIni ? 'border border-emas-500 bg-emas-50 text-arang hover:bg-emas-100/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500 focus-visible:ring-offset-2 focus-visible:ring-offset-tulang' : 'btn-sekunder' }}">
                    Minggu Ini
                </a>

                <a href="{{ route('admin.piket.index', ['minggu' => $mingguBerikutnya]) }}"
                   class="btn btn-sekunder px-3 py-2 text-xs">
                    Minggu Berikutnya
                </a>
            </nav>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4">
            <a href="{{ route('admin.piket.create') }}" class="btn btn-utama">
                Tambah Piket
            </a>

            <form method="POST" action="{{ route('admin.piket.salin') }}"
                  onsubmit="return confirm('Salin jadwal minggu ini ke minggu berikutnya?')">
                @csrf
                <input type="hidden" name="minggu" value="{{ $minggu->toDateString() }}">

                <button type="submit" class="btn btn-sekunder">
                    Salin ke Minggu Depan
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel mingguan untuk layar sedang ke atas --}}
    <div class="mt-4 hidden overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Hari</th>
                        @foreach ($sesi as $label)
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($hari as $item)
                        @php $apakahHariIni = $item['tanggal']->isToday(); @endphp
                        <tr class="{{ $apakahHariIni ? 'bg-laut-50' : '' }} transition-colors hover:bg-laut-50/60">
                            <td class="w-44 px-4 py-3 align-top text-sm {{ $apakahHariIni ? 'border-l-4 border-emas-500' : '' }}">
                                <span class="flex flex-wrap items-center gap-2 font-medium text-arang">
                                    {{ $item['label'] }}
                                    @if ($apakahHariIni)
                                        <span class="badge bg-emas-100 text-emas-800 ring-1 ring-inset ring-emas-300">Hari ini</span>
                                    @endif
                                </span>
                                <span class="mt-0.5 block text-xs text-gray-500">{{ $item['tanggalLabel'] }}</span>
                            </td>

                            @foreach ($sesi as $kode => $label)
                                <td class="px-4 py-3 align-top text-sm">
                                    @php
                                        $entri = $jadwal[$item['tanggal']->toDateString()][$kode] ?? [];
                                    @endphp

                                    @forelse ($entri as $baris)
                                        <div class="mb-2 rounded-lg border border-laut-100 bg-white px-3 py-2 shadow-sm last:mb-0">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="font-medium text-arang">{{ $baris->user?->name ?? 'Staf terhapus' }}</span>

                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.piket.edit', $baris) }}"
                                                       class="btn btn-sekunder px-2 py-1 text-xs">
                                                        Ubah
                                                    </a>

                                                    <x-konfirmasi-hapus :action="route('admin.piket.destroy', $baris)"
                                                                        :judul="'piket '.($baris->user?->name ?? 'staf')"
                                                                        teks="Hapus" />
                                                </div>
                                            </div>

                                            @if ($baris->catatan)
                                                <p class="mt-1 text-xs text-gray-500">{{ $baris->catatan }}</p>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-400">Belum ada</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kartu harian untuk layar HP --}}
    <div class="mt-4 space-y-3 sm:hidden">
        @foreach ($hari as $item)
            @php
                $apakahHariIniHp = $item['tanggal']->isToday();
            @endphp
            <section aria-label="Piket {{ $item['label'] }}"
                     class="rounded-xl border bg-white p-4 shadow-sm {{ $apakahHariIniHp ? 'border-emas-500 border-l-4 bg-laut-50' : 'border-laut-100' }}">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h3 class="font-judul text-base font-semibold text-laut-800">{{ $item['label'] }}</h3>
                        <p class="text-xs text-gray-500">{{ $item['tanggalLabel'] }}</p>
                    </div>
                    @if ($apakahHariIniHp)
                        <span class="badge bg-emas-100 text-emas-800 ring-1 ring-inset ring-emas-300">Hari ini</span>
                    @endif
                </div>

                @foreach ($sesi as $kode => $label)
                    <p class="mt-3 text-xs font-medium text-gray-500">{{ $label }}</p>
                    @php $entriHp = $jadwal[$item['tanggal']->toDateString()][$kode] ?? []; @endphp
                    @forelse ($entriHp as $baris)
                        <div class="mt-2 rounded-lg border border-laut-100 bg-white px-3 py-2 shadow-sm">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-sm font-medium text-arang">{{ $baris->user?->name ?? 'Staf terhapus' }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.piket.edit', $baris) }}" class="btn btn-sekunder px-2 py-1 text-xs">Ubah</a>
                                    <x-konfirmasi-hapus :action="route('admin.piket.destroy', $baris)"
                                                        :judul="'piket '.($baris->user?->name ?? 'staf')"
                                                        teks="Hapus" />
                                </div>
                            </div>
                            @if ($baris->catatan)
                                <p class="mt-1 text-xs text-gray-500">{{ $baris->catatan }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="mt-2 text-xs text-gray-400">Belum ada</p>
                    @endforelse
                @endforeach
            </section>
        @endforeach
    </div>
</x-admin-layout>
