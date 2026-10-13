<x-admin-layout title="Arsip Proposal">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <h2 class="font-judul text-lg font-semibold text-laut-800">Arsip Proposal</h2>
        <p class="mt-1 text-sm leading-relaxed text-gray-600">
            Semua proposal dari seluruh kegiatan. Gunakan pencarian dan filter tahun untuk menemukannya.
        </p>

        <form method="GET" action="{{ route('admin.arsip.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-72"
                              :value="$q" placeholder="Cari judul proposal atau nama kegiatan" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="tahun" value="Tahun" />
                <select id="tahun" name="tahun"
                        class="input-baku mt-1 block w-full sm:w-40">
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
                   class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $proposals->total() }} proposal.</p>
    </div>

    {{-- Tabel untuk layar sedang ke atas --}}
    <div class="mt-4 hidden overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Judul Proposal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Berdasarkan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Diunggah</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-laut-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($proposals as $proposal)
                        <tr class="transition-colors hover:bg-laut-50/60">
                            <td class="px-4 py-3 text-sm">
                                <span class="font-medium text-arang">{{ $proposal->judul }}</span>
                                @if ($proposal->catatan)
                                    <span class="block text-xs text-gray-500">{{ $proposal->catatan }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if ($proposal->event)
                                    <a href="{{ route('admin.event.show', $proposal->event) }}"
                                       class="font-medium text-laut-700 underline-offset-2 hover:text-laut-800 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
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
                                    <span class="font-medium text-arang">{{ $proposal->templateDari->judul }}</span>
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
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.arsip.unduh', $proposal) }}"
                                       class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                        Unduh
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                Belum ada proposal yang cocok.
                                <span class="mt-1 block text-xs">Ubah kata kunci atau filter tahun di atas.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar bertumpuk untuk layar HP --}}
    <div class="mt-4 space-y-3 sm:hidden">
        @forelse ($proposals as $proposal)
            <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm">
                <p class="text-sm font-semibold text-arang">{{ $proposal->judul }}</p>
                @if ($proposal->catatan)
                    <p class="mt-0.5 text-xs text-gray-500">{{ $proposal->catatan }}</p>
                @endif
                <p class="mt-2 text-xs text-gray-600">
                    Kegiatan:
                    @if ($proposal->event)
                        <span class="font-medium text-arang">{{ $proposal->event->nama }}</span>
                        <span class="text-gray-500">({{ $proposal->event->tahun }})</span>
                    @else
                        -
                    @endif
                </p>
                <p class="mt-1 text-xs text-gray-600">
                    Berdasarkan:
                    @if ($proposal->templateDari)
                        <span class="font-medium text-arang">{{ $proposal->templateDari->judul }}</span>
                    @else
                        -
                    @endif
                </p>
                <p class="mt-1 text-xs text-gray-500">Diunggah {{ $proposal->created_at->format('d/m/Y') }}</p>
                <div class="mt-3 border-t border-gray-100 pt-3">
                    <a href="{{ route('admin.arsip.unduh', $proposal) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Unduh</a>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-laut-100 bg-white p-6 text-center shadow-sm">
                <p class="text-sm text-gray-500">Belum ada proposal yang cocok.</p>
                <p class="mt-1 text-xs text-gray-400">Ubah kata kunci atau filter tahun di atas.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $proposals->links() }}
    </div>
</x-admin-layout>
