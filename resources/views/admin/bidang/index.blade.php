<x-admin-layout title="Bidang">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Daftar Bidang</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Bidang adalah pembagian kerja staf, misalnya Sekretaris, Bendahara, Humas.
                </p>
            </div>

            <a href="{{ route('admin.bidang.create') }}"
               class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                Tambah Bidang
            </a>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Jumlah Staf</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($bidangs as $bidang)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $bidang->nama }}</td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $bidang->staf_profiles_count }} staf
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.bidang.edit', $bidang) }}"
                                       class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Edit
                                    </a>

                                    @if ($bidang->staf_profiles_count > 0)
                                        <button type="button" disabled
                                                class="inline-flex cursor-not-allowed items-center rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-400 opacity-50">
                                            Hapus
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.bidang.destroy', $bidang) }}"
                                              onsubmit="return confirm('Hapus bidang {{ $bidang->nama }}?')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-red-300 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if ($bidang->staf_profiles_count > 0)
                                    <p class="mt-1 text-xs text-red-600">
                                        Tidak bisa dihapus: masih dipakai {{ $bidang->staf_profiles_count }} staf.
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada bidang. Tambahkan bidang pertama lewat tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
