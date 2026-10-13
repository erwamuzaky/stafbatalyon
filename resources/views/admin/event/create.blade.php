<x-admin-layout title="Tambah Kegiatan">
    <x-card title="Tambah Kegiatan Baru" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Isi data dasar kegiatan. Proposal bisa diunggah setelah kegiatan disimpan.
        </p>

        <form method="POST" action="{{ route('admin.event.store') }}" class="mt-6 space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div>
                <x-input-label for="nama" value="Nama Kegiatan" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                              :value="old('nama')" required autofocus placeholder="Contoh: Pentas Seni Akhir Tahun" />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tahun" value="Tahun" />
                <x-text-input id="tahun" name="tahun" type="number" min="2000" max="2100"
                              class="mt-1 block w-full" :value="old('tahun', date('Y'))" required />
                <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                              class="mt-1 block w-full" :value="old('tanggal_mulai')" required />
                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tanggal_selesai" value="Tanggal Selesai (opsional)" />
                <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date"
                              class="mt-1 block w-full" :value="old('tanggal_selesai')" />
                <p class="mt-1 text-xs text-gray-500">Biarkan kosong kalau kegiatan hanya satu hari.</p>
                <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" required class="input-baku mt-1 block w-full">
                    @foreach ($statusPilihan as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('status', 'rencana') === $nilai)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan kegiatan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.event.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
