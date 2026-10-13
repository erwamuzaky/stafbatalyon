<x-guest-layout judul="Lupa Kata Sandi">
    <h1 class="text-2xl text-arang">Lupa Kata Sandi</h1>

    <div class="mb-4 mt-2 max-w-md text-sm leading-relaxed text-arang/75">
        Tidak masalah. Masukkan alamat email Anda, lalu kami kirim tautan untuk membuat kata sandi baru.
    </div>

    <!-- Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="mt-1 text-sm text-arang/70">Pastikan email yang dimasukkan sama dengan email akun Anda.</p>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Kirim Tautan
            </x-primary-button>
        </div>

        <p class="mt-4 text-sm">
            <a class="rounded-md text-laut-600 underline hover:text-laut-700" href="{{ route('login') }}">
                Kembali
            </a>
        </p>
    </form>
</x-guest-layout>
