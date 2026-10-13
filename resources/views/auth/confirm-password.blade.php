<x-guest-layout judul="Konfirmasi Kata Sandi" sambutan="Masukkan kata sandi lagi untuk melanjutkan.">
    <h1 class="text-2xl text-arang">Konfirmasi Kata Sandi</h1>

    <p class="mb-4 mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Ini bagian yang dilindungi. Masukkan kata sandi Anda dulu sebelum melanjutkan.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4"
          x-data="{ memproses: false }" @submit="memproses = true">
        @csrf

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1 block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center" x-bind:disabled="memproses">
            <span x-show="!memproses">Lanjutkan</span>
            <span x-show="memproses" x-cloak>Memproses...</span>
        </x-primary-button>

        <div class="flex justify-end">
            <x-secondary-button onclick="history.back()">
                Batal
            </x-secondary-button>
        </div>
    </form>
</x-guest-layout>
