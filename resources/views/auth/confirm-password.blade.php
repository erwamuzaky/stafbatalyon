<x-guest-layout judul="Konfirmasi Kata Sandi">
    <h1 class="text-2xl text-arang">Konfirmasi Kata Sandi</h1>

    <div class="mb-4 mt-2 max-w-md text-sm leading-relaxed text-arang/75">
        Ini bagian yang dilindungi. Masukkan kata sandi Anda dulu sebelum melanjutkan.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Kata Sandi -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Lanjutkan
            </x-primary-button>
        </div>

        <div class="mt-4 flex justify-end">
            <x-secondary-button onclick="history.back()">
                Batal
            </x-secondary-button>
        </div>
    </form>
</x-guest-layout>
