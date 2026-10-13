<x-admin-layout title="Bidang">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-judul text-lg font-semibold text-laut-800">Daftar Bidang</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600">
                    Bidang adalah pembagian kerja staf, misalnya Sekretaris, Bendahara, Humas.
                </p>
            </div>

            <a href="{{ route('admin.bidang.create') }}" class="btn btn-utama">Tambah Bidang</a>
        </div>
    </div>

    {{-- Tabel untuk layar sedang ke atas --}}
    <div class="mt-4 hidden overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Nama Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Jumlah Staf</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-laut-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($bidangs as $bidang)
                        <tr class="transition-colors hover:bg-laut-50/60">
                            <td class="px-4 py-3 text-sm font-medium text-arang">{{ $bidang->nama }}</td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $bidang->staf_profiles_count }} staf
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('admin.bidang.edit', $bidang) }}"
                                       class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                        Ubah
                                    </a>

                                    @if ($bidang->staf_profiles_count > 0)
                                        <button type="button" disabled
                                                class="btn cursor-not-allowed px-2.5 py-1.5 text-xs opacity-50 btn-bahaya">
                                            Hapus
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.bidang.destroy', $bidang) }}"
                                              onsubmit="return confirm('Hapus bidang {{ $bidang->nama }}?')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                    class="btn btn-bahaya px-2.5 py-1.5 text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if ($bidang->staf_profiles_count > 0)
                                    <p class="mt-1 text-right text-xs text-marun-600">
                                        Tidak bisa dihapus: masih dipakai {{ $bidang->staf_profiles_count }} staf.
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-sm text-gray-500">
                                Belum ada bidang. Tambahkan bidang pertama lewat tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar bertumpuk untuk layar HP --}}
    <div class="mt-4 space-y-3 sm:hidden">
        @forelse ($bidangs as $bidang)
            <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-arang">{{ $bidang->nama }}</p>
                    <span class="badge badge-berjalan">{{ $bidang->staf_profiles_count }} staf</span>
                </div>
                @if ($bidang->staf_profiles_count > 0)
                    <p class="mt-2 text-xs text-marun-600">Tidak bisa dihapus: masih dipakai {{ $bidang->staf_profiles_count }} staf.</p>
                @endif
                <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-100 pt-3">
                    <a href="{{ route('admin.bidang.edit', $bidang) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Ubah</a>
                    @if ($bidang->staf_profiles_count > 0)
                        <button type="button" disabled class="btn btn-bahaya cursor-not-allowed px-2.5 py-1.5 text-xs opacity-50">Hapus</button>
                    @else
                        <form method="POST" action="{{ route('admin.bidang.destroy', $bidang) }}"
                              onsubmit="return confirm('Hapus bidang {{ $bidang->nama }}?')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-bahaya px-2.5 py-1.5 text-xs">Hapus</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-laut-100 bg-white p-6 text-center shadow-sm">
                <p class="text-sm text-gray-500">Belum ada bidang. Tambahkan bidang pertama lewat tombol di atas.</p>
            </div>
        @endforelse
    </div>
</x-admin-layout>
