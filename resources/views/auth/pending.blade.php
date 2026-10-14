<x-guest-layout judul="Menunggu Persetujuan Admin" sambutan="Akun Anda sedang menunggu persetujuan admin.">
    <div class="text-center">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-laut-50 text-laut-600">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>

        <x-badge status="menunggu" label="Menunggu Persetujuan Admin" class="mt-5" />
        <h1 class="mt-3 text-2xl text-arang">Menunggu Persetujuan Admin</h1>
    </div>

    <p class="mt-3 text-center text-sm leading-relaxed text-arang/80">
        Terima kasih sudah mendaftar. Akun Anda sudah dibuat, tetapi belum bisa dipakai
        untuk masuk sampai diperiksa admin.
    </p>

    <x-alert type="peringatan" class="mt-5">
        Admin Staf Batalyon akan memeriksa data Anda lebih dulu. Setelah disetujui, Anda
        bisa masuk memakai email dan kata sandi yang tadi didaftarkan.
    </x-alert>

    <div class="mt-5 rounded-2xl border border-laut-100 bg-laut-50 p-5">
        <p class="font-judul text-sm font-semibold text-laut-800">Langkah berikutnya</p>
        <ul class="mt-3 space-y-2.5 text-sm text-arang/80">
            <li class="flex gap-3">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500" aria-hidden="true"></span>
                <span>Tunggu kabar persetujuan dari pengurus atau sekretaris.</span>
            </li>
            <li class="flex gap-3">
                <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500" aria-hidden="true"></span>
                <span>Kalau sudah lebih dari 1x24 jam dan belum ada kabar, hubungi pengurus atau sekretaris Staf Batalyon.</span>
            </li>
        </ul>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <x-primary-button class="h-12 w-full justify-center">
            Keluar
        </x-primary-button>
    </form>

    <p class="mt-4 text-center text-sm">
        <a class="rounded-md text-laut-600 underline decoration-laut-200 underline-offset-2 hover:text-laut-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500" href="{{ route('login') }}">
            Ke Halaman Masuk
        </a>
    </p>
</x-guest-layout>
