<x-admin-layout title="Tambah Bidang">
    <x-card title="Tambah Bidang Baru" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Bidang dipakai untuk pembagian kerja staf. Contoh: Sekretaris, Bendahara, Humas.
        </p>

        <form method="POST" action="{{ route('admin.bidang.store') }}" class="mt-6 space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div>
                <x-input-label for="nama" value="Nama Bidang" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                              :value="old('nama')" required autofocus placeholder="Contoh: Humas" />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div class="flex items-start gap-2 rounded-lg border border-gray-200 bg-gray-50/50 px-3 py-2.5">
                <input type="hidden" name="tampil_di_beranda" value="0">
                <input id="tampil_di_beranda" name="tampil_di_beranda" type="checkbox" value="1" @checked(old('tampil_di_beranda'))
                       class="mt-0.5 rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                <div>
                    <label for="tampil_di_beranda" class="text-sm text-arang">Tampilkan di halaman depan</label>
                    <p class="text-xs text-gray-500">Staf bidang ini ikut tampil di bagan halaman depan.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan bidang</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.bidang.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
