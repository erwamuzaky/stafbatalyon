<x-admin-layout title="Surat">
    <x-card title="Surat & SK" class="max-w-7xl">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.surat.index') }}" class="flex flex-wrap items-center gap-2">
                <select name="jenis" class="input-baku">
                    <option value="">Semua jenis</option>
                    @foreach ($jenisPilihan as $k => $v)
                        <option value="{{ $k }}" @selected($jenis === $k)>{{ $v['label'] }}</option>
                    @endforeach
                </select>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari nomor / perihal / penandatangan"
                       class="input-baku w-64 max-w-full">
                <button type="submit" class="btn btn-sekunder">Saring</button>
            </form>

            <a href="{{ route('admin.surat.create') }}" class="btn btn-utama">
                <x-icon name="tambah" class="h-4 w-4" />
                Buat surat
            </a>
        </div>

        <x-tabel-responsif>
            <x-slot name="headers">
                <tr>
                    <th class="w-40">Nomor</th>
                    <th class="w-36">Jenis</th>
                    <th>Perihal</th>
                    <th class="w-28">Tanggal</th>
                    <th class="w-32">Tujuan/Periode</th>
                    <th class="w-28 text-right">Aksi</th>
                </tr>
            </x-slot>
            @forelse ($surats as $surat)
                <tr>
                    <td class="font-judul tracking-wide">{{ $surat->nomor }}</td>
                    <td>{{ $surat->labelJenis() }}</td>
                    <td>
                        <span class="line-clamp-2">{{ $surat->perihal }}</span>
                        @if ($surat->tujuan)
                            <br><span class="text-xs text-gray-500">kepada: {{ $surat->tujuan }}</span>
                        @endif
                    </td>
                    <td>{{ $surat->tanggal?->format('d/m/Y') }}</td>
                    <td>
                        @if ($surat->periode)
                            <span class="text-xs">{{ $surat->periode->nama }}</span>
                        @elseif ($surat->event)
                            <span class="text-xs">{{ $surat->event->nama }}</span>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.surat.cetak', $surat) }}" target="_blank" class="ikon-aksi"
                               title="Cetak PDF" aria-label="Cetak surat {{ $surat->nomor }}">
                                <x-icon name="unduh" />
                            </a>
                            <x-aksi :href="route('admin.surat.edit', $surat)" ikon="ubah" label="Ubah surat" />

                            <x-konfirmasi-hapus :action="route('admin.surat.destroy', $surat)"
                                                :judul="'surat '.$surat->nomor" teks="Hapus surat" ikon="hapus" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-6 text-center">
                        <x-empty title="Belum ada surat" ikon="dokumen">
                            Buat surat pertama dengan tombol&nbsp;“Buat surat” di atas.
                        </x-empty>
                    </td>
                </tr>
            @endforelse
        </x-tabel-responsif>

        @if ($surats->hasPages())
            <div class="mt-4">{{ $surats->links() }}</div>
        @endif
    </x-card>
</x-admin-layout>
