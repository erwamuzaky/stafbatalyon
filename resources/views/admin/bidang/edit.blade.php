<x-admin-layout title="Ubah Bidang">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Ubah Bidang</h2>

            <form method="POST" action="{{ route('admin.bidang.update', $bidang) }}" class="mt-6 space-y-5">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="nama" value="Nama Bidang" />
                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                                  :value="old('nama', $bidang->nama)" required autofocus />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.bidang.index') }}" class="btn btn-sekunder">
                        Batal
                    </a>
                </div>
            </form>

            <div class="mt-6 rounded-lg border border-laut-100 bg-laut-50 px-3 py-2.5 text-sm text-gray-700">
                <p>Jumlah staf di bidang ini: <span class="font-semibold text-arang">{{ $bidang->stafProfiles()->count() }}</span></p>

                @if ($bidang->stafProfiles()->count() > 0)
                    <p class="mt-1 text-xs text-gray-500">
                        Bidang ini belum bisa dihapus karena masih dipakai staf.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
