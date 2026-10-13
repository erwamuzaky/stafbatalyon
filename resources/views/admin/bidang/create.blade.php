<x-admin-layout title="Tambah Bidang">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="text-base font-semibold text-gray-800">Tambah Bidang Baru</h2>
            <p class="mt-1 text-sm text-gray-600">
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

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.bidang.index') }}"
                       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
