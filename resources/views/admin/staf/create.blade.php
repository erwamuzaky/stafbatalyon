<x-admin-layout title="Tambah Staf">
    <x-card title="Tambah Staf Baru" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Staf yang ditambahkan admin langsung berstatus <span class="font-medium text-arang">disetujui</span>,
            jadi bisa masuk memakai email dan kata sandi di bawah ini.
        </p>

        <form method="POST" action="{{ route('admin.staf.store') }}" class="mt-6 space-y-5"
              enctype="multipart/form-data"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div>
                <x-input-label for="name" value="Nama Lengkap" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                              :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                              :value="old('email')" required autocomplete="off" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Kata Sandi" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                              required autocomplete="new-password" />
                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Ulangi Kata Sandi" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                              class="mt-1 block w-full" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="bidang_id" value="Bidang" />
                <select id="bidang_id" name="bidang_id" required class="input-baku mt-1 block w-full">
                    <option value="">-- Pilih Bidang --</option>
                    @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}" @selected(old('bidang_id') == $bidang->id)>
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('bidang_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="kelas" value="Kelas" />
                <x-text-input id="kelas" name="kelas" type="text" class="mt-1 block w-full"
                              :value="old('kelas')" required placeholder="Contoh: XI RPL 1" />
                <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jabatan" value="Jabatan (opsional)" />
                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                              :value="old('jabatan')" placeholder="Contoh: Ketua Bidang" />
                <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_hp" value="Nomor HP (opsional)" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full"
                              :value="old('no_hp')" placeholder="08xxxxxxxxxx" autocomplete="tel" />
                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
            </div>

            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50/50 px-3 py-2.5">
                <input type="hidden" name="is_inti" value="0">
                <input id="is_inti" name="is_inti" type="checkbox" value="1" @checked(old('is_inti'))
                       class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                <label for="is_inti" class="text-sm text-arang">Staf inti (pengurus)</label>
            </div>

            <div>
                <x-input-label for="foto" value="Foto (opsional)" />
                <input id="foto" name="foto" type="file" accept="image/*"
                       class="input-baku mt-1 block w-full p-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-laut-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-laut-700">
                <p class="mt-1 text-xs text-gray-500">Format jpg/png/webp, maksimal 2 MB.</p>
                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan staf</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.staf.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
