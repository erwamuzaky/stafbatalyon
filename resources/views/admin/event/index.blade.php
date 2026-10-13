<x-admin-layout title="Event">
    <x-card>
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Daftar Kegiatan</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Setiap kegiatan bisa menyimpan satu atau beberapa proposal sebagai arsip.
                    </p>
                </div>
                <a href="{{ route('admin.event.create') }}" class="btn btn-utama">
                    <x-icon name="tambah" class="h-4 w-4" />
                    Tambah kegiatan
                </a>
            </div>
        </x-slot>

        {{-- Filter dan pencarian --}}
        <form method="GET" action="{{ route('admin.event.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama kegiatan" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="tahun" value="Tahun" />
                <select id="tahun" name="tahun" class="input-baku mt-1 block w-full sm:w-40">
                    <option value="">Semua tahun</option>
                    @foreach ($tahunPilihan as $pilihan)
                        <option value="{{ $pilihan }}" @selected((string) $tahun === (string) $pilihan)>
                            {{ $pilihan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="input-baku mt-1 block w-full sm:w-40">
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
                   class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $events->total() }} kegiatan.</p>
    </x-card>

    <x-tabel-responsif :headers="['Nama Kegiatan', 'Tahun', 'Tanggal', 'Status', 'Proposal', 'Aksi']" class="mt-4">
        @forelse ($events as $event)
            <tr class="transition-colors hover:bg-laut-50/50">
                <td class="px-4 py-3">
                    <a href="{{ route('admin.event.show', $event) }}"
                       class="font-medium text-laut-700 underline-offset-2 hover:text-laut-800 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
                        {{ $event->nama }}
                    </a>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $event->tahun }}</td>
                <td class="px-4 py-3 text-gray-700">
                    {{ $event->tanggal_mulai->format('d/m/Y') }}
                    @if ($event->tanggal_selesai)
                        <span class="block text-xs text-gray-500">s.d. {{ $event->tanggal_selesai->format('d/m/Y') }}</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-badge :status="$event->status" :label="$statusPilihan[$event->status] ?? $event->status" />
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $event->proposals_count }} berkas</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <x-aksi :href="route('admin.event.show', $event)" ikon="mata" label="Detail kegiatan" />
                        <x-aksi :href="route('admin.event.edit', $event)" ikon="ubah" label="Ubah kegiatan" />
                        <x-konfirmasi-hapus :action="route('admin.event.destroy', $event)"
                                            :judul="'kegiatan '.$event->nama"
                                            pesan="Semua proposal dan berkasnya di kegiatan ini ikut terhapus."
                                            teks="Hapus kegiatan" ikon="hapus" />
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-10">
                    <x-empty title="Belum ada kegiatan" ikon="event">
                        Tambahkan kegiatan lewat tombol di atas.
                    </x-empty>
                </td>
            </tr>
        @endforelse

        <x-slot name="mobile">
            @forelse ($events as $event)
                <div class="rounded-xl border border-laut-100 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <a href="{{ route('admin.event.show', $event) }}"
                           class="text-sm font-semibold text-laut-700 hover:text-laut-800 hover:underline">{{ $event->nama }}</a>
                        <x-badge :status="$event->status" :label="$statusPilihan[$event->status] ?? $event->status" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Tahun {{ $event->tahun }} &middot; {{ $event->tanggal_mulai->format('d/m/Y') }}
                        @if ($event->tanggal_selesai)
                            s.d. {{ $event->tanggal_selesai->format('d/m/Y') }}
                        @endif
                        &middot; {{ $event->proposals_count }} berkas
                    </p>
                    <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3">
                        <x-aksi :href="route('admin.event.show', $event)" ikon="mata" label="Detail kegiatan" />
                        <x-aksi :href="route('admin.event.edit', $event)" ikon="ubah" label="Ubah kegiatan" />
                        <x-konfirmasi-hapus :action="route('admin.event.destroy', $event)"
                                            :judul="'kegiatan '.$event->nama"
                                            pesan="Semua proposal dan berkasnya di kegiatan ini ikut terhapus."
                                            teks="Hapus kegiatan" ikon="hapus" />
                    </div>
                </div>
            @empty
                <x-empty title="Belum ada kegiatan" ikon="event">
                    Tambahkan kegiatan lewat tombol di atas.
                </x-empty>
            @endforelse
        </x-slot>
    </x-tabel-responsif>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</x-admin-layout>
