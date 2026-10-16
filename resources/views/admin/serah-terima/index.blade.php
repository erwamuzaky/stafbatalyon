<x-admin-layout title="Serah Terima">
    <x-card>
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Serah Terima Kepengurusan</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Catatan penyerahan tugas dari periode sebelumnya ke periode baru.
                    </p>
                </div>
                <a href="{{ route('admin.serah-terima.create') }}" class="btn btn-utama">
                    <x-icon name="tambah" class="h-4 w-4" />
                    Catat serah terima
                </a>
            </div>
        </x-slot>

        <x-tabel-responsif :headers="['Tanggal', 'Periode Baru', 'Dari Periode', 'Tempat', 'Berkas', 'Aksi']">
            @forelse ($serahTerima as $item)
                <tr class="transition-colors hover:bg-laut-50/50">
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-medium text-arang">{{ $item->periode?->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $item->periodeLama?->nama ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $item->tempat ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if ($item->file_path)
                            <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" rel="noopener"
                               class="ikon-aksi" title="Buka berkas" aria-label="Buka berkas">
                                <x-icon name="unduh" />
                            </a>
                        @else
                            <span class="text-gray-400">&ndash;</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <x-aksi :href="route('admin.serah-terima.edit', $item)" ikon="ubah" label="Ubah serah terima" />
                            <x-konfirmasi-hapus :action="route('admin.serah-terima.destroy', $item)"
                                                :judul="'serah terima '.$item->tanggal->format('d/m/Y')"
                                                teks="Hapus serah terima" ikon="hapus" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10">
                        <x-empty title="Belum ada serah terima" ikon="dokumen">
                            Catat serah terima pertama lewat tombol di atas.
                        </x-empty>
                    </td>
                </tr>
            @endforelse

            <x-slot name="mobile">
                @forelse ($serahTerima as $item)
                    <div class="rounded-xl border border-laut-100 p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-arang">{{ $item->periode?->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-500">dari {{ $item->periodeLama?->nama ?? '-' }}</p>
                            </div>
                            <span class="whitespace-nowrap text-xs text-gray-500">{{ $item->tanggal->format('d/m/Y') }}</span>
                        </div>
                        @if ($item->tempat)
                            <p class="mt-2 text-xs text-gray-600">Tempat: {{ $item->tempat }}</p>
                        @endif
                        <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3">
                            @if ($item->file_path)
                                <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" rel="noopener"
                                   class="ikon-aksi" title="Buka berkas" aria-label="Buka berkas">
                                    <x-icon name="unduh" />
                                </a>
                            @endif
                            <x-aksi :href="route('admin.serah-terima.edit', $item)" ikon="ubah" label="Ubah serah terima" />
                            <x-konfirmasi-hapus :action="route('admin.serah-terima.destroy', $item)"
                                                :judul="'serah terima '.$item->tanggal->format('d/m/Y')"
                                                teks="Hapus serah terima" ikon="hapus" />
                        </div>
                    </div>
                @empty
                    <x-empty title="Belum ada serah terima" ikon="dokumen">
                        Catat serah terima pertama lewat tombol di atas.
                    </x-empty>
                @endforelse
            </x-slot>
        </x-tabel-responsif>
    </x-card>
</x-admin-layout>
