<x-admin-layout title="Tambah Bidang">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Tambah Bidang Baru</h2>
            <p class="mt-1 text-sm leading-relaxed text-gray-600">
                Bidang dipakai untuk pembagian kerja staf. Contoh: Sekretaris, Bendahara, Humas.
            </p>

            <form method="POST" action="{{ route('admin.bidang.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <x-input-label for="nama" value="Nama Bidang" />
                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                                  :value="old('nama')" required autofocus placeholder="Contoh: Humas" />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.bidang.index') }}" class="btn btn-sekunder">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
