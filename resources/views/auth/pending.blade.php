<x-guest-layout>
    <div class="text-center">
        <h1 class="text-lg font-semibold text-gray-800">Menunggu Persetujuan Admin</h1>

        <p class="mt-4 text-sm text-gray-600">
            Terima kasih sudah mendaftar. Akun Anda sudah dibuat, tetapi
            belum bisa dipakai untuk masuk.
        </p>

        <p class="mt-3 text-sm text-gray-600">
            Admin Staf Batalyon akan memeriksa data Anda lebih dulu. Setelah disetujui,
            Anda bisa masuk memakai email dan kata sandi yang tadi didaftarkan.
        </p>

        <p class="mt-3 text-sm text-gray-600">
            Kalau sudah lebih dari 1x24 jam dan belum ada kabar, silakan hubungi admin sekolah.
        </p>

        <a href="{{ route('login') }}"
           class="inline-block mt-6 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Ke Halaman Masuk
        </a>
    </div>
</x-guest-layout>
