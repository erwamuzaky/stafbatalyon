<x-admin-layout title="Event">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Daftar Kegiatan</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Setiap kegiatan bisa menyimpan satu atau beberapa proposal sebagai arsip.
                </p>
            </div>

            <a href="{{ route('admin.event.create') }}"
               class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                Tambah Kegiatan
            </a>
        </div>

        {{-- Filter dan pencarian --}}
        <form method="GET" action="{{ route('admin.event.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div>
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama kegiatan" />
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

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-40">
                    <option value="">Semua status</option>
                    @foreach ($statusPilihan as $nilai => $label)
                        <option value="{{ $nilai }}" @selected((string) $status === (string) $nilai)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Cari</x-primary-button>

            @if (($q ?? '') !== '' || ($tahun ?? '') !== '' || ($status ?? '') !== '')
                <a href="{{ route('admin.event.index') }}"
                   class="text-sm font-medium text-gray-600 underline hover:text-gray-900">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $events->total() }} kegiatan.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Proposal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($events as $event)
                        @php
                            $badge = match ($event->status) {
                                'rencana' => 'bg-amber-100 text-amber-800',
                                'berjalan' => 'bg-indigo-100 text-indigo-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-700',
                            };
                            $label = $statusPilihan[$event->status] ?? $event->status;
                        @endphp

                        <tr>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.event.show', $event) }}"
                                   class="font-medium text-gray-900 hover:text-indigo-600 hover:underline">
                                    {{ $event->nama }}
                                </a>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">{{ $event->tahun }}</td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $event->tanggal_mulai->format('d/m/Y') }}
                                @if ($event->tanggal_selesai)
                                    <span class="block text-xs text-gray-500">
                                        s.d. {{ $event->tanggal_selesai->format('d/m/Y') }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $event->proposals_count }} berkas
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.event.show', $event) }}"
                                       class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.event.edit', $event) }}"
                                       class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Edit
                                    </a>

                                    <x-konfirmasi-hapus :action="route('admin.event.destroy', $event)"
                                                        :judul="'kegiatan '.$event->nama"
                                                        pesan="Semua proposal dan berkasnya di kegiatan ini ikut terhapus." />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada kegiatan yang cocok. Tambahkan kegiatan lewat tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</x-admin-layout>
