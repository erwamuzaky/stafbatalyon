<x-guest-layout judul="Menunggu Persetujuan Admin">
    <div>
        <span class="badge badge-menunggu">Menunggu Persetujuan Admin</span>

        <h1 class="mt-3 text-2xl text-arang">Menunggu Persetujuan Admin</h1>

        <p class="mt-2 max-w-md text-sm leading-relaxed text-arang/80">
            Terima kasih sudah mendaftar. Akun Anda sudah dibuat, tetapi
            belum bisa dipakai untuk masuk.
        </p>

        <p class="mt-3 max-w-md text-sm leading-relaxed text-arang/80">
            Admin Staf Batalyon akan memeriksa data Anda lebih dulu. Setelah disetujui,
            Anda bisa masuk memakai email dan kata sandi yang tadi didaftarkan.
        </p>

        <div class="mt-4 rounded-lg border border-laut-200 bg-laut-50 px-4 py-3">
            <p class="text-sm font-semibold text-laut-700">Langkah berikutnya</p>
            <ul class="mt-1 list-disc space-y-1 pl-5 text-sm text-arang/80">
                <li>Tunggu kabar persetujuan dari pengurus atau sekretaris.</li>
                <li>Kalau sudah lebih dari 1x24 jam dan belum ada kabar, hubungi pengurus atau sekretaris Staf Batalyon.</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <x-primary-button class="w-full justify-center">
                Keluar
            </x-primary-button>
        </form>

        <p class="mt-4 text-center text-sm">
            <a class="rounded-md text-laut-600 underline hover:text-laut-700" href="{{ route('login') }}">
                Ke Halaman Masuk
            </a>
        </p>
    </div>
</x-guest-layout>
