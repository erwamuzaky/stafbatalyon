<x-guest-layout judul="Masuk" sambutan="Masuk untuk melihat jadwal piket dan info kepengurusan Anda.">
    <h1 class="text-2xl text-arang">Masuk</h1>
    <p class="mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Hanya akun staf yang sudah disetujui admin yang bisa masuk.
    </p>

    @if (session('status'))
        <x-alert type="sukses" class="mt-4">{{ session('status') }}</x-alert>
    @endif

    @if ($errors->has('email'))
        <x-alert type="error" class="mt-4">{{ $errors->first('email') }}</x-alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4"
          x-data="{ memproses: false }" @submit="memproses = true">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <p class="mt-1 text-xs text-arang/70">Pakai email yang Anda daftarkan.</p>
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500" name="remember">
            <span class="ms-2 text-sm text-arang/80">Ingat saya</span>
        </label>

        <x-primary-button class="h-12 w-full justify-center" x-bind:disabled="memproses">
            <span x-show="!memproses">Masuk</span>
            <span x-show="memproses" x-cloak>Memproses...</span>
        </x-primary-button>
    </form>

    <div class="mt-5 flex flex-col gap-1.5 text-sm">
        @if (Route::has('password.request'))
            <a class="rounded-md text-laut-600 underline decoration-laut-200 underline-offset-2 hover:text-laut-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500" href="{{ route('password.request') }}">
                Lupa kata sandi?
            </a>
        @endif

        <a class="rounded-md text-laut-600 underline decoration-laut-200 underline-offset-2 hover:text-laut-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500" href="{{ route('register') }}">
            Belum punya akun? Daftar di sini
        </a>
    </div>
</x-guest-layout>
