<x-guest-layout>
    <h1 class="text-lg font-semibold text-gray-800">Konfirmasi Kata Sandi</h1>

    <div class="mt-2 mb-4 text-sm text-gray-600">
        Ini bagian yang dilindungi. Masukkan kata sandi Anda dulu sebelum melanjutkan.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Kata Sandi -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                Lanjutkan
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
