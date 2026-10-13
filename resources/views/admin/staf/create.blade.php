<x-admin-layout title="Tambah Staf">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="text-base font-semibold text-gray-800">Tambah Staf Baru</h2>
            <p class="mt-1 text-sm text-gray-600">
                Staf yang ditambahkan admin langsung berstatus <span class="font-medium">disetujui</span>,
                jadi bisa masuk memakai email dan kata sandi di bawah ini.
            </p>

            <form method="POST" action="{{ route('admin.staf.store') }}" class="mt-6 space-y-5">
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
                    <select id="bidang_id" name="bidang_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_inti" value="0">
                    <input id="is_inti" name="is_inti" type="checkbox" value="1" @checked(old('is_inti'))
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="is_inti" class="text-sm text-gray-700">Staf inti (pengurus)</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.staf.index') }}"
                       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
