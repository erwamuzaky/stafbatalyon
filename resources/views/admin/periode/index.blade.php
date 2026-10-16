<x-admin-layout title="Periode">
    <x-card>
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Periode Kepengurusan</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Periode jadi acuan tahun kepengurusan. Kegiatan dan serah terima bisa ditautkan ke periode ini.
                    </p>
                </div>
                <a href="{{ route('admin.periode.create') }}" class="btn btn-utama">
                    <x-icon name="tambah" class="h-4 w-4" />
                    Tambah periode
                </a>
            </div>
        </x-slot>

        <x-tabel-responsif :headers="['Periode', 'Rentang', 'Status', 'Kegiatan', 'Serah Terima', 'Aksi']">
            @forelse ($periodes as $periode)
                <tr class="transition-colors hover:bg-laut-50/50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-arang">{{ $periode->nama }}</p>
                        <p class="text-xs text-gray-500">{{ $periode->tahun_mulai }}/{{ $periode->tahun_selesai }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        {{ $periode->tanggal_mulai->format('d/m/Y') }}
                        &ndash;
                        {{ $periode->tanggal_selesai?->format('d/m/Y') ?? 'sekarang' }}
                    </td>
                    <td class="px-4 py-3">
                        @if ($periode->isAktif())
                            <x-badge status="berjalan" label="Aktif" />
                        @else
                            <x-badge status="selesai" label="Selesai" />
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $periode->events_count }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $periode->serah_terima_count }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <x-aksi :href="route('admin.periode.edit', $periode)" ikon="ubah" label="Ubah periode" />

                            @if ($periode->events_count > 0 || $periode->serah_terima_count > 0)
                                <button type="button" disabled
                                        class="ikon-aksi ikon-aksi-bahaya cursor-not-allowed opacity-40"
                                        title="Tidak bisa dihapus: masih terhubung kegiatan atau serah terima"
                                        aria-label="Tidak bisa dihapus">
                                    <x-icon name="hapus" />
                                </button>
                            @else
                                <x-konfirmasi-hapus :action="route('admin.periode.destroy', $periode)"
                                                    :judul="'periode '.$periode->nama" teks="Hapus periode" ikon="hapus" />
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10">
                        <x-empty title="Belum ada periode" ikon="kalender">
                            Tambahkan periode kepengurusan pertama lewat tombol di atas.
                        </x-empty>
                    </td>
                </tr>
            @endforelse

            <x-slot name="mobile">
                @forelse ($periodes as $periode)
                    <div class="rounded-xl border border-laut-100 p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-arang">{{ $periode->nama }}</p>
                                <p class="text-xs text-gray-500">{{ $periode->tahun_mulai }}/{{ $periode->tahun_selesai }}</p>
                            </div>
                            @if ($periode->isAktif())
                                <x-badge status="berjalan" label="Aktif" />
                            @else
                                <x-badge status="selesai" label="Selesai" />
                            @endif
                        </div>
                        <dl class="mt-3 space-y-1 text-xs text-gray-600">
                            <div class="flex justify-between gap-2">
                                <dt>Rentang</dt>
                                <dd class="text-right">
                                    {{ $periode->tanggal_mulai->format('d/m/Y') }} &ndash;
                                    {{ $periode->tanggal_selesai?->format('d/m/Y') ?? 'sekarang' }}
                                </dd>
                            </div>
                            <div class="flex justify-between gap-2">
                                <dt>Kegiatan</dt>
                                <dd>{{ $periode->events_count }}</dd>
                            </div>
                            <div class="flex justify-between gap-2">
                                <dt>Serah terima</dt>
                                <dd>{{ $periode->serah_terima_count }}</dd>
                            </div>
                        </dl>
                        <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3">
                            <x-aksi :href="route('admin.periode.edit', $periode)" ikon="ubah" label="Ubah periode" />

                            @if ($periode->events_count > 0 || $periode->serah_terima_count > 0)
                                <button type="button" disabled
                                        class="ikon-aksi ikon-aksi-bahaya cursor-not-allowed opacity-40"
                                        title="Tidak bisa dihapus" aria-label="Tidak bisa dihapus">
                                    <x-icon name="hapus" />
                                </button>
                            @else
                                <x-konfirmasi-hapus :action="route('admin.periode.destroy', $periode)"
                                                    :judul="'periode '.$periode->nama" teks="Hapus periode" ikon="hapus" />
                            @endif
                        </div>
                    </div>
                @empty
                    <x-empty title="Belum ada periode" ikon="kalender">
                        Tambahkan periode kepengurusan pertama lewat tombol di atas.
                    </x-empty>
                @endforelse
            </x-slot>
        </x-tabel-responsif>
    </x-card>
</x-admin-layout>
