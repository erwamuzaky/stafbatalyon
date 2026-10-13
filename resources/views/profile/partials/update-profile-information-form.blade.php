<section>
    <header>
        <h2 class="font-judul text-lg font-semibold text-laut-800">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm leading-relaxed text-gray-600">
            Ubah nama dan alamat email akun Anda di sini.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5"
          x-data="{ memproses: false }" @submit="memproses = true">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        Alamat email Anda belum diverifikasi.

                        <button form="send-verification" class="rounded-md text-sm text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-sukses-600">
                            Tautan verifikasi baru sudah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if ($user->stafProfile)
            <div>
                <x-input-label for="no_hp" value="Nomor HP" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $user->stafProfile?->no_hp)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
            </div>
        @else
            <p class="text-sm text-gray-500">Nomor HP hanya untuk akun staf.</p>
        @endif

        <div class="flex flex-wrap items-center gap-4 pt-1">
            <x-primary-button x-bind:disabled="memproses">
                <span x-show="!memproses">Simpan perubahan</span>
                <span x-show="memproses" x-cloak>Menyimpan...</span>
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
