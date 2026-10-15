<x-guest-layout judul="Daftar Akun Staf" sambutan="Daftar sekali, lalu tunggu persetujuan admin sebelum bisa masuk.">
    <h1 class="text-2xl text-arang">Daftar Akun Staf</h1>
    <p class="mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Isi data di bawah ini dengan benar. Akun baru bisa dipakai setelah disetujui admin.
    </p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-6"
          enctype="multipart/form-data"
          x-data="{ memproses: false }" @submit="memproses = true">
        @csrf

        {{-- Data akun --}}
        <fieldset>
            <legend class="flex w-full items-center gap-3">
                <span class="font-judul text-sm font-semibold text-laut-800">Data akun</span>
                <span class="h-px flex-1 bg-laut-100" aria-hidden="true"></span>
            </legend>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    <p class="mt-1 text-xs text-arang/70">Email ini dipakai untuk masuk setelah akun disetujui.</p>
                </div>

                <div>
                    <x-input-label for="password" value="Kata Sandi" />
                    <x-text-input id="password" class="mt-1 block w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Ulangi Kata Sandi" />
                    <x-text-input id="password_confirmation" class="mt-1 block w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </fieldset>

        {{-- Bidang dan kontak --}}
        <fieldset class="mt-6">
            <legend class="flex w-full items-center gap-3">
                <span class="font-judul text-sm font-semibold text-laut-800">Bidang dan kontak</span>
                <span class="h-px flex-1 bg-laut-100" aria-hidden="true"></span>
            </legend>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="kelas" value="Kelas" />
                    <x-text-input id="kelas" class="mt-1 block w-full" type="text" name="kelas" :value="old('kelas')" required placeholder="Contoh: XI RPL 1" />
                    <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="bidang_id" value="Bidang" />
                    <select id="bidang_id" name="bidang_id" required class="input-baku mt-1 block w-full">
                        <option value="">-- Pilih Bidang --</option>
                        @foreach (($bidangs ?? []) as $bidang)
                            <option value="{{ $bidang->id }}" @selected(old('bidang_id') == $bidang->id)>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('bidang_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="jabatan" value="Jabatan (opsional)" />
                    <x-text-input id="jabatan" class="mt-1 block w-full" type="text" name="jabatan" :value="old('jabatan')" placeholder="Contoh: Anggota" />
                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="no_hp" value="Nomor HP" />
                    <x-text-input id="no_hp" class="mt-1 block w-full" type="text" name="no_hp" :value="old('no_hp')" required placeholder="08xxxxxxxxxx" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                    <p class="mt-1 text-xs text-arang/70">Contoh: 081234567890. Admin memakai nomor ini untuk menghubungi Anda.</p>
                </div>
            </div>
        </fieldset>

        {{-- Foto profil (opsional) --}}
        <fieldset class="mt-6" x-data="{
                    pratinjau: null,
                    namaBerkas: '',
                    pilih(e) {
                        const berkas = e.target.files && e.target.files[0];
                        if (!berkas) { this.buang(); return; }
                        if (this.pratinjau) URL.revokeObjectURL(this.pratinjau);
                        this.pratinjau = URL.createObjectURL(berkas);
                        this.namaBerkas = berkas.name;
                    },
                    buang() {
                        if (this.pratinjau) URL.revokeObjectURL(this.pratinjau);
                        this.pratinjau = null;
                        this.namaBerkas = '';
                        if (this.$refs.foto) this.$refs.foto.value = '';
                    }
                }">
            <legend class="flex w-full items-center gap-3">
                <span class="font-judul text-sm font-semibold text-laut-800">Foto profil (opsional)</span>
                <span class="h-px flex-1 bg-laut-100" aria-hidden="true"></span>
            </legend>

            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="relative aspect-square w-28 shrink-0 self-center overflow-hidden rounded-2xl border border-laut-100 bg-laut-50 sm:w-32 sm:self-start">
                    <template x-if="pratinjau">
                        <img :src="pratinjau" alt="Pratinjau foto" class="h-full w-full object-cover">
                    </template>
                    <template x-if="!pratinjau">
                        <div class="flex h-full w-full items-center justify-center text-laut-400">
                            <x-icon name="profil" class="h-10 w-10" />
                        </div>
                    </template>
                </div>

                <div class="min-w-0 flex-1 space-y-2">
                    <input id="foto" x-ref="foto" name="foto" type="file" accept="image/*"
                           class="peer sr-only" @change="pilih($event)">
                    <label for="foto"
                           class="btn btn-sekunder cursor-pointer peer-focus-visible:ring-2 peer-focus-visible:ring-laut-500 peer-focus-visible:ring-offset-2">
                        Pilih foto
                    </label>

                    <p x-cloak x-show="namaBerkas" class="truncate text-sm text-arang">
                        <span x-text="namaBerkas"></span>
                    </p>

                    <button type="button" x-cloak x-show="pratinjau" @click="buang()"
                            class="text-sm font-medium text-marun-600 underline decoration-marun-200 underline-offset-2 hover:text-marun-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-marun-500">
                        Buang pilihan
                    </button>

                    <p class="text-xs text-arang/70">Boleh dikosongkan. Maksimal 2 MB, format jpg, png, atau webp.</p>
                    <x-input-error :messages="$errors->get('foto')" />
                </div>
            </div>
        </fieldset>

        <x-primary-button class="h-12 w-full justify-center" x-bind:disabled="memproses">
            <span x-show="!memproses">Daftar</span>
            <span x-show="memproses" x-cloak>Mengirim...</span>
        </x-primary-button>
    </form>

    <p class="mt-5 text-sm">
        <a class="rounded-md text-laut-600 underline decoration-laut-200 underline-offset-2 hover:text-laut-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500" href="{{ route('login') }}">
            Sudah punya akun? Masuk
        </a>
    </p>
</x-guest-layout>
