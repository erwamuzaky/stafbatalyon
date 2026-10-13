<x-guest-layout judul="Lupa Kata Sandi" sambutan="Kami bantu kirim tautan untuk membuat kata sandi baru.">
    <h1 class="text-2xl text-arang">Lupa Kata Sandi</h1>

    <p class="mb-4 mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Tidak masalah. Masukkan alamat email Anda, lalu kami kirim tautan untuk membuat kata sandi baru.
    </p>

    @if (session('status'))
        <x-alert type="sukses" class="mb-4">{{ session('status') }}</x-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4"
          x-data="{ memproses: false }" @submit="memproses = true">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="mt-1 text-xs text-arang/70">Pastikan email yang dimasukkan sama dengan email akun Anda.</p>
        </div>

        <x-primary-button class="w-full justify-center" x-bind:disabled="memproses">
            <span x-show="!memproses">Kirim Tautan</span>
            <span x-show="memproses" x-cloak>Mengirim...</span>
        </x-primary-button>
    </form>

    <p class="mt-5 text-sm">
        <a class="rounded-md text-laut-600 underline decoration-laut-200 underline-offset-2 hover:text-laut-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500" href="{{ route('login') }}">
            Kembali ke halaman masuk
        </a>
    </p>
</x-guest-layout>
