<x-guest-layout judul="Buat Kata Sandi Baru">
    <h1 class="text-2xl text-arang">Buat Kata Sandi Baru</h1>
    <p class="mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Masukkan email akun Anda lalu buat kata sandi baru.
    </p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-4">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi Baru" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Ulangi Kata Sandi -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Ulangi Kata Sandi Baru" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Simpan Kata Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
