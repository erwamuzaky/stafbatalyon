<x-admin-layout title="Event">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-judul text-lg font-semibold text-laut-800">Daftar Kegiatan</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600">
                    Setiap kegiatan bisa menyimpan satu atau beberapa proposal sebagai arsip.
                </p>
            </div>

            <a href="{{ route('admin.event.create') }}" class="btn btn-utama">Tambah Kegiatan</a>
        </div>

        {{-- Filter dan pencarian --}}
        <form method="GET" action="{{ route('admin.event.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama kegiatan" />
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

            <div class="w-full sm:w-auto">
                <x-input-label for="status" value="Status" />
                <select id="status" name="status"
                        class="input-baku mt-1 block w-full sm:w-40">
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
    </div>

    {{-- Tabel untuk layar sedang ke atas --}}
    <div class="mt-4 hidden overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Proposal</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-laut-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($events as $event)
                        @php
                            $badge = match ($event->status) {
                                'rencana' => 'badge-rencana',
                                'berjalan' => 'badge-berjalan',
                                'selesai' => 'badge-selesai',
                                default => 'badge-selesai',
                            };
                            $label = $statusPilihan[$event->status] ?? $event->status;
                        @endphp

                        <tr class="transition-colors hover:bg-laut-50/60">
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('admin.event.show', $event) }}"
                                   class="font-medium text-laut-700 underline-offset-2 hover:text-laut-800 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
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
                                <span class="badge {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $event->proposals_count }} berkas
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('admin.event.show', $event) }}"
                                       class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.event.edit', $event) }}"
                                       class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                        Ubah
                                    </a>

                                    <x-konfirmasi-hapus :action="route('admin.event.destroy', $event)"
                                                        :judul="'kegiatan '.$event->nama"
                                                        pesan="Semua proposal dan berkasnya di kegiatan ini ikut terhapus." />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                Belum ada kegiatan yang cocok. Tambahkan kegiatan lewat tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar bertumpuk untuk layar HP --}}
    <div class="mt-4 space-y-3 sm:hidden">
        @forelse ($events as $event)
            @php
                $badgeHp = match ($event->status) {
                    'rencana' => 'badge-rencana',
                    'berjalan' => 'badge-berjalan',
                    'selesai' => 'badge-selesai',
                    default => 'badge-selesai',
                };
                $labelHp = $statusPilihan[$event->status] ?? $event->status;
            @endphp
            <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <a href="{{ route('admin.event.show', $event) }}"
                       class="text-sm font-semibold text-laut-700 hover:text-laut-800 hover:underline">{{ $event->nama }}</a>
                    <span class="badge {{ $badgeHp }} shrink-0">{{ $labelHp }}</span>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Tahun {{ $event->tahun }} &middot; {{ $event->tanggal_mulai->format('d/m/Y') }}
                    @if ($event->tanggal_selesai)
                        s.d. {{ $event->tanggal_selesai->format('d/m/Y') }}
                    @endif
                    &middot; {{ $event->proposals_count }} berkas
                </p>
                <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-100 pt-3">
                    <a href="{{ route('admin.event.show', $event) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Detail</a>
                    <a href="{{ route('admin.event.edit', $event) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Ubah</a>
                    <x-konfirmasi-hapus :action="route('admin.event.destroy', $event)"
                                        :judul="'kegiatan '.$event->nama"
                                        pesan="Semua proposal dan berkasnya di kegiatan ini ikut terhapus." />
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-laut-100 bg-white p-6 text-center shadow-sm">
                <p class="text-sm text-gray-500">Belum ada kegiatan yang cocok. Tambahkan kegiatan lewat tombol di atas.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</x-admin-layout>
