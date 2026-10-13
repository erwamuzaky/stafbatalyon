<x-admin-layout title="Bidang">
    <x-card>
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Daftar Bidang</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Bidang adalah pembagian kerja staf, misalnya Sekretaris, Bendahara, Humas.
                    </p>
                </div>
                <a href="{{ route('admin.bidang.create') }}" class="btn btn-utama">
                    <x-icon name="tambah" class="h-4 w-4" />
                    Tambah bidang
                </a>
            </div>
        </x-slot>

        <x-tabel-responsif :headers="['Nama Bidang', 'Jumlah Staf', 'Aksi']">
            @forelse ($bidangs as $bidang)
                <tr class="transition-colors hover:bg-laut-50/50">
                    <td class="px-4 py-3 font-medium text-arang">{{ $bidang->nama }}</td>
                    <td class="px-4 py-3 text-gray-700">
                        <x-badge status="berjalan" :label="$bidang->staf_profiles_count.' staf'" />
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <x-aksi :href="route('admin.bidang.edit', $bidang)" ikon="ubah" label="Ubah bidang" />

                            @if ($bidang->staf_profiles_count > 0)
                                <button type="button" disabled
                                        class="ikon-aksi ikon-aksi-bahaya cursor-not-allowed opacity-40"
                                        title="Tidak bisa dihapus: masih dipakai {{ $bidang->staf_profiles_count }} staf"
                                        aria-label="Tidak bisa dihapus">
                                    <x-icon name="hapus" />
                                </button>
                            @else
                                <x-konfirmasi-hapus :action="route('admin.bidang.destroy', $bidang)"
                                                    :judul="'bidang '.$bidang->nama" teks="Hapus bidang" ikon="hapus" />
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-10">
                        <x-empty title="Belum ada bidang" ikon="bidang">
                            Tambahkan bidang pertama lewat tombol di atas.
                        </x-empty>
                    </td>
                </tr>
            @endforelse

            <x-slot name="mobile">
                @forelse ($bidangs as $bidang)
                    <div class="rounded-xl border border-laut-100 p-4">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-semibold text-arang">{{ $bidang->nama }}</p>
                            <x-badge status="berjalan" :label="$bidang->staf_profiles_count.' staf'" />
                        </div>
                        @if ($bidang->staf_profiles_count > 0)
                            <p class="mt-2 text-xs text-marun-600">Tidak bisa dihapus: masih dipakai {{ $bidang->staf_profiles_count }} staf.</p>
                        @endif
                        <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3">
                            <x-aksi :href="route('admin.bidang.edit', $bidang)" ikon="ubah" label="Ubah bidang" />
                            @if ($bidang->staf_profiles_count > 0)
                                <button type="button" disabled
                                        class="ikon-aksi ikon-aksi-bahaya cursor-not-allowed opacity-40"
                                        title="Tidak bisa dihapus" aria-label="Tidak bisa dihapus">
                                    <x-icon name="hapus" />
                                </button>
                            @else
                                <x-konfirmasi-hapus :action="route('admin.bidang.destroy', $bidang)"
                                                    :judul="'bidang '.$bidang->nama" teks="Hapus bidang" ikon="hapus" />
                            @endif
                        </div>
                    </div>
                @empty
                    <x-empty title="Belum ada bidang" ikon="bidang">
                        Tambahkan bidang pertama lewat tombol di atas.
                    </x-empty>
                @endforelse
            </x-slot>
        </x-tabel-responsif>
    </x-card>
</x-admin-layout>
