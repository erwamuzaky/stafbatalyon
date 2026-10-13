<x-admin-layout title="Piket Gerbang">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Jadwal Piket Gerbang</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Minggu {{ $hari[0]['label'] }} {{ $hari[0]['tanggalLabel'] }}
                    &ndash; {{ $hari[count($hari) - 1]['label'] }} {{ $hari[count($hari) - 1]['tanggalLabel'] }}
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    Setiap hari hanya ada satu jadwal piket gerbang. Bisa diisi beberapa staf sekaligus.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.piket.index', ['minggu' => $mingguSebelumnya]) }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                    Minggu Sebelumnya
                </a>

                <a href="{{ route('admin.piket.index') }}"
                   class="inline-flex items-center rounded-md border px-3 py-2 text-xs font-semibold uppercase tracking-widest shadow-sm {{ $minggu->toDateString() === $mingguIni ? 'border-indigo-200 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' }}">
                    Minggu Ini
                </a>

                <a href="{{ route('admin.piket.index', ['minggu' => $mingguBerikutnya]) }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                    Minggu Berikutnya
                </a>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.piket.create') }}"
               class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                Tambah Piket
            </a>

            <form method="POST" action="{{ route('admin.piket.salin') }}"
                  onsubmit="return confirm('Salin jadwal minggu ini ke minggu berikutnya?')">
                @csrf
                <input type="hidden" name="minggu" value="{{ $minggu->toDateString() }}">

                <button type="submit"
                        class="inline-flex items-center rounded-md border border-indigo-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-indigo-700 shadow-sm hover:bg-indigo-50">
                    Salin ke Minggu Depan
                </button>
            </form>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Hari</th>
                        @foreach ($sesi as $label)
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ $label }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($hari as $item)
                        <tr>
                            <td class="w-40 px-4 py-3 align-top text-sm">
                                <span class="block font-medium text-gray-900">{{ $item['label'] }}</span>
                                <span class="block text-xs text-gray-500">{{ $item['tanggalLabel'] }}</span>
                            </td>

                            @foreach ($sesi as $kode => $label)
                                <td class="px-4 py-3 align-top text-sm">
                                    @php
                                        $entri = $jadwal[$item['tanggal']->toDateString()][$kode] ?? [];
                                    @endphp

                                    @forelse ($entri as $baris)
                                        <div class="mb-2 rounded-md border border-gray-200 px-3 py-2 last:mb-0">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <span class="font-medium text-gray-900">{{ $baris->user?->name ?? 'Staf terhapus' }}</span>

                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.piket.edit', $baris) }}"
                                                       class="inline-flex items-center rounded-md border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                                        Edit
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
</x-admin-layout>
