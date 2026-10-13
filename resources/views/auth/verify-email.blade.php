<x-guest-layout judul="Verifikasi Email">
    <h1 class="text-2xl text-arang">Verifikasi Email</h1>

    <div class="mb-4 mt-2 max-w-md text-sm leading-relaxed text-arang/75">
        Terima kasih sudah mendaftar! Sebelum mulai, mohon verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirim ke email Anda. Jika Anda belum menerima emailnya, kami akan mengirimkan yang baru.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            Tautan verifikasi baru sudah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button class="w-full justify-center">
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
