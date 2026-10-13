<x-admin-layout title="Arsip Proposal">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <h2 class="text-base font-semibold text-gray-800">Arsip Proposal</h2>
        <p class="mt-1 text-sm text-gray-600">
            Semua proposal dari seluruh kegiatan. Gunakan pencarian dan filter tahun untuk menemukannya.
        </p>

        <form method="GET" action="{{ route('admin.arsip.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div>
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-72"
                              :value="$q" placeholder="Cari judul proposal atau nama kegiatan" />
            </div>

            <div>
                <x-input-label for="tahun" value="Tahun" />
                <select id="tahun" name="tahun"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-40">
                    <option value="">Semua tahun</option>
                    @foreach ($tahunPilihan as $pilihan)
                        <option value="{{ $pilihan }}" @selected((string) $tahun === (string) $pilihan)>
                            {{ $pilihan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Cari</x-primary-button>

            @if (($q ?? '') !== '' || ($tahun ?? '') !== '')
                <a href="{{ route('admin.arsip.index') }}"
                   class="text-sm font-medium text-gray-600 underline hover:text-gray-900">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $proposals->total() }} proposal.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Judul Proposal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Berdasarkan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Diunggah</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($proposals as $proposal)
                        <tr>
                            <td class="px-4 py-3 text-sm">
                                <span class="font-medium text-gray-900">{{ $proposal->judul }}</span>
                                @if ($proposal->catatan)
                                    <span class="block text-xs text-gray-500">{{ $proposal->catatan }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if ($proposal->event)
                                    <a href="{{ route('admin.event.show', $proposal->event) }}"
                                       class="font-medium text-indigo-600 hover:underline">
                                        {{ $proposal->event->nama }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $proposal->event?->tahun ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if ($proposal->templateDari)
                                    <span class="font-medium text-gray-900">{{ $proposal->templateDari->judul }}</span>
                                    @if ($proposal->templateDari->event)
                                        <span class="block text-xs text-gray-500">
                                            kegiatan {{ $proposal->templateDari->event->nama }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $proposal->created_at->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.arsip.unduh', $proposal) }}"
                                   class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    Unduh
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada proposal yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $proposals->links() }}
    </div>
</x-admin-layout>
