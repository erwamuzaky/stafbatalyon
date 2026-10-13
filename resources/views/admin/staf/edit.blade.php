<x-admin-layout title="Ubah Data Staf">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Ubah Data Staf</h2>

            <div class="mt-3 rounded-lg border border-laut-100 bg-laut-50 px-3 py-2.5 text-sm text-gray-700">
                <p><span class="font-medium text-arang">Nama:</span> {{ $user->name }}</p>
                <p><span class="font-medium text-arang">Email:</span> {{ $user->email }}</p>
                <p class="mt-1 text-xs text-gray-500">
                    Nama dan email tidak bisa diubah dari halaman ini. Kalau perlu diubah, ubah lewat Kelola Akun.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.staf.update', $user) }}" class="mt-6 space-y-5">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="bidang_id" value="Bidang" />
                    <select id="bidang_id" name="bidang_id" required
                            class="input-baku mt-1 block w-full">
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

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.staf.index') }}" class="btn btn-sekunder">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4 rounded-xl border border-marun-200 bg-marun-50 p-4 sm:p-6">
        <div class="max-w-xl">
            <h2 class="font-judul text-lg font-semibold text-marun-600">Hapus Staf Ini</h2>
            <p class="mt-1 text-sm leading-relaxed text-marun-600/90">
                Akun staf beserta data profilnya akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.
            </p>

            <form method="POST" action="{{ route('admin.staf.destroy', $user) }}" class="mt-4"
                  onsubmit="return confirm('Hapus staf {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-bahaya">Hapus Staf</button>
            </form>
        </div>
    </div>
</x-admin-layout>
