<x-guest-layout>
    <h1 class="text-lg font-semibold text-gray-800">Lupa Kata Sandi</h1>

    <div class="mt-2 mb-4 text-sm text-gray-600">
        Tidak masalah. Masukkan alamat email Anda, lalu kami kirim tautan untuk membuat kata sandi baru.
    </div>

    <!-- Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Kirim Tautan
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
