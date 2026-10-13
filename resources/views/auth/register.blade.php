<x-guest-layout judul="Daftar Akun Staf" sambutan="Daftar sekali, lalu tunggu persetujuan admin sebelum bisa masuk.">
    <h1 class="text-2xl text-arang">Daftar Akun Staf</h1>
    <p class="mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Isi data di bawah ini dengan benar. Akun baru bisa dipakai setelah disetujui admin.
    </p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-6"
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
                    <x-input-label for="no_hp" value="Nomor HP" />
                    <x-text-input id="no_hp" class="mt-1 block w-full" type="text" name="no_hp" :value="old('no_hp')" required placeholder="08xxxxxxxxxx" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                    <p class="mt-1 text-xs text-arang/70">Contoh: 081234567890. Admin memakai nomor ini untuk menghubungi Anda.</p>
                </div>
            </div>
        </fieldset>

        <x-primary-button class="w-full justify-center" x-bind:disabled="memproses">
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
