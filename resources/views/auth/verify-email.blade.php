<x-guest-layout judul="Verifikasi Email" sambutan="Verifikasi email untuk mengaktifkan akun Anda.">
    <h1 class="text-2xl text-arang">Verifikasi Email</h1>

    <p class="mb-4 mt-1 max-w-md text-sm leading-relaxed text-arang/75">
        Terima kasih sudah mendaftar! Sebelum mulai, mohon verifikasi alamat email Anda
        dengan mengeklik tautan yang baru saja kami kirim ke email Anda. Jika Anda belum
        menerima emailnya, kami akan mengirimkan yang baru.
    </p>

    @if (session('status') == 'verification-link-sent')
        <x-alert type="sukses" class="mb-4">Tautan verifikasi baru sudah dikirim ke email Anda.</x-alert>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button class="h-12 w-full justify-center">
                Kirim Ulang Email Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-secondary-button type="submit" class="w-full justify-center">
                Keluar
            </x-secondary-button>
        </form>
    </div>
</x-guest-layout>
