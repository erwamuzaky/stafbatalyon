<x-admin-layout title="Ubah Data Staf">
    <x-card title="Ubah Data Staf" class="max-w-2xl">
        <div class="rounded-lg border border-laut-100 bg-laut-50 px-3 py-2.5 text-sm text-gray-700">
            <p><span class="font-medium text-arang">Nama:</span> {{ $user->name }}</p>
            <p><span class="font-medium text-arang">Email:</span> {{ $user->email }}</p>
            <p class="mt-1 text-xs text-gray-500">
                Nama dan email tidak bisa diubah dari halaman ini. Kalau perlu diubah, ubah lewat Kelola Akun.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.staf.update', $user) }}" class="mt-6 space-y-5"
              enctype="multipart/form-data"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="bidang_id" value="Bidang" />
                <select id="bidang_id" name="bidang_id" required class="input-baku mt-1 block w-full">
                    <option value="">-- Pilih Bidang --</option>
                    @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}"
                                @selected((string) old('bidang_id', $profile?->bidang_id) === (string) $bidang->id)>
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('bidang_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="kelas" value="Kelas" />
                <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full"
                              :value="old('kelas', $profile?->kelas)" required placeholder="Contoh: XI RPL 1" />
                <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jabatan" value="Jabatan (opsional)" />
                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                              :value="old('jabatan', $profile?->jabatan)" placeholder="Contoh: Ketua Bidang" />
                <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_hp" value="Nomor HP (opsional)" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full"
                              :value="old('no_hp', $profile?->no_hp)" placeholder="08xxxxxxxxxx" autocomplete="tel" />
                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
            </div>

            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50/50 px-3 py-2.5">
                <input type="hidden" name="is_inti" value="0">
                <input id="is_inti" name="is_inti" type="checkbox" value="1"
                       @checked(old('is_inti', $profile?->is_inti))
                       class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                <label for="is_inti" class="text-sm text-arang">Staf inti (pengurus)</label>
            </div>

            @if ($profile?->foto_url)
            <div class="rounded-lg border border-laut-100 bg-laut-50 p-4">
                <div class="mb-2 text-sm font-medium text-arang">Foto saat ini</div>
                <x-avatar :name="$user->name" :foto="$profile->foto_url" size="lg" />
            </div>
            @endif

            <div>
                <x-input-label for="foto" value="Foto baru (opsional)" />
                <input id="foto" name="foto" type="file" accept="image/*"
                       class="input-baku mt-1 block w-full p-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-laut-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-laut-700">
                <p class="mt-1 text-xs text-gray-500">Format jpg/png/webp, maksimal 2 MB.</p>
                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
            </div>

            @if ($profile?->foto_url)
            <div class="flex items-center gap-2">
                <input id="hapus_foto" name="hapus_foto" type="hidden" value="0">
                <input id="hapus_foto_checkbox" name="hapus_foto" type="checkbox" value="1"
                       class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                <label for="hapus_foto_checkbox" class="text-sm text-arang">Hapus foto saat ini</label>
            </div>
            @endif

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan perubahan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.staf.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>

    <x-card class="mt-4 max-w-2xl border-marun-200 bg-marun-50">
        <h3 class="font-judul text-lg font-semibold text-marun-700">Hapus Staf Ini</h3>
        <p class="mt-1 text-sm text-marun-600/90">
            Akun staf beserta data profilnya akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.
        </p>

        <form method="POST" action="{{ route('admin.staf.destroy', $user) }}" class="mt-4"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-bahaya" x-bind:disabled="memproses">
                <span x-show="!memproses">Hapus staf</span>
                <span x-show="memproses" x-cloak>Menghapus...</span>
            </button>
        </form>
    </x-card>
</x-admin-layout>
