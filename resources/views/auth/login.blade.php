<x-guest-layout judul="Masuk">
    <h1 class="text-2xl text-arang">Masuk</h1>
    <p class="mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Hanya akun staf yang sudah disetujui admin yang bisa masuk.
    </p>

    <!-- Status -->
    <x-auth-session-status class="mb-4 mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="mt-1 text-sm text-arang/70">Pakai email yang Anda daftarkan. Kalau gagal masuk, periksa tulisan email dan kata sandi Anda.</p>
        </div>

        <!-- Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Ingat Saya -->
        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500" name="remember">
                <span class="ms-2 text-sm text-arang/80">Ingat saya</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Masuk
            </x-primary-button>
        </div>

        <div class="mt-4 flex flex-col gap-1 text-sm">
            @if (Route::has('password.request'))
                <a class="rounded-md text-laut-600 underline hover:text-laut-700 focus:outline-none focus:ring-2 focus:ring-laut-500 focus:ring-offset-2" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif

            <a class="rounded-md text-laut-600 underline hover:text-laut-700 focus:outline-none focus:ring-2 focus:ring-laut-500 focus:ring-offset-2" href="{{ route('register') }}">
                Belum punya akun? Daftar di sini
            </a>
        </div>
    </form>
</x-guest-layout>
