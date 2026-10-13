<x-admin-layout title="Dashboard">
    {{-- Angka ringkasan: kartu Menunggu ditonjolkan, sisanya tenang --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Staf Aktif</p>
            <p class="mt-2 font-judul text-4xl font-semibold text-laut-800">{{ $jumlahStaf }}</p>
            <p class="mt-1 text-xs leading-relaxed text-gray-500">Staf biasa yang sudah disetujui admin.</p>
        </div>

        <a href="{{ route('admin.akun.index', ['status' => 'pending']) }}"
           class="rounded-xl border-2 border-emas-500 bg-emas-50 p-5 shadow-sm transition-colors hover:bg-emas-100/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
            <span class="flex items-center justify-between gap-2">
                <span class="text-sm font-semibold text-arang">Menunggu Persetujuan</span>
                @if ($jumlahPending > 0)
                    <span class="badge bg-emas-500 text-white">Perlu dicek</span>
                @else
                    <span class="badge badge-disetujui">Aman</span>
                @endif
            </span>
            <span class="mt-2 block font-judul text-4xl font-semibold {{ $jumlahPending > 0 ? 'text-laut-800' : 'text-gray-400' }}">{{ $jumlahPending }}</span>
            <span class="mt-1 block text-xs font-medium leading-relaxed text-laut-700">
                Buka Kelola Akun untuk memeriksa pendaftar
            </span>
        </a>

        <a href="{{ route('admin.event.index') }}"
           class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm transition-colors hover:bg-laut-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
            <p class="text-sm font-medium text-gray-500">Kegiatan (Event)</p>
            <p class="mt-2 font-judul text-4xl font-semibold text-laut-800">{{ $jumlahEvent }}</p>
            <p class="mt-1 text-xs font-medium leading-relaxed text-laut-700">
                Lihat daftar kegiatan
            </p>
        </a>

        <a href="{{ route('admin.piket.index') }}"
           class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm transition-colors hover:bg-laut-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">
            <p class="text-sm font-medium text-gray-500">Piket Minggu Ini</p>
            <p class="mt-2 font-judul text-4xl font-semibold text-laut-800">{{ $jumlahPiketMingguIni }}</p>
            <p class="mt-1 text-xs font-medium leading-relaxed text-laut-700">
                Atur pembagian piket gerbang
            </p>
        </a>
    </div>

    {{-- Dua panel pendek memakai angka yang sudah ada (tanpa query baru) --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <div class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Antrean persetujuan</h2>
            @if ($jumlahPending > 0)
                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                    Ada <span class="font-semibold text-arang">{{ $jumlahPending }} pendaftar</span> yang menunggu
                    keputusan. Periksa data mereka, lalu setujui atau tolak supaya tidak menumpuk.
                </p>
            @else
                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                    Belum ada pendaftar yang menunggu. Semua akun sudah diproses, tinggal pantau
                    pendaftar baru sesekali.
                </p>
            @endif
            <a href="{{ route('admin.akun.index', ['status' => 'pending']) }}"
               class="btn btn-sekunder mt-4 px-3 py-1.5 text-xs">Buka Kelola Akun</a>
        </div>

        <div class="rounded-xl border border-laut-100 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Piket minggu berjalan</h2>
            @if ($jumlahPiketMingguIni > 0)
                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                    Sudah ada <span class="font-semibold text-arang">{{ $jumlahPiketMingguIni }} penugasan piket</span>
                    pada Senin sampai Sabtu minggu ini. Buka jadwal untuk menambah atau merapikan.
                </p>
            @else
                <p class="mt-2 text-sm leading-relaxed text-gray-600">
                    Belum ada jadwal piket minggu ini. Silakan tambahkan lewat halaman Piket Gerbang
                    supaya gerbang tetap terjaga.
                </p>
            @endif
            <a href="{{ route('admin.piket.index') }}"
               class="btn btn-sekunder mt-4 px-3 py-1.5 text-xs">Buka Piket Gerbang</a>
        </div>
    </div>

    <div class="mt-4 rounded-xl border border-laut-100 bg-white p-5 shadow-sm sm:p-6">
        <h2 class="font-judul text-lg font-semibold text-laut-800">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="mt-2 text-sm leading-relaxed text-gray-600">
            Dari halaman ini Anda bisa mengelola akun staf, data staf, dan daftar bidang.
        </p>

        <ul class="mt-4 space-y-2 text-sm text-gray-600">
            <li class="flex gap-2">
                <span aria-hidden="true" class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500"></span>
                <span>Periksa pendaftar baru di
                    <a href="{{ route('admin.akun.index') }}" class="font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">Kelola Akun</a>.
                </span>
            </li>
            <li class="flex gap-2">
                <span aria-hidden="true" class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500"></span>
                <span>Ubah data staf di
                    <a href="{{ route('admin.staf.index') }}" class="font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">Data Staf</a>.
                </span>
            </li>
            <li class="flex gap-2">
                <span aria-hidden="true" class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500"></span>
                <span>Tambah atau ubah bidang di
                    <a href="{{ route('admin.bidang.index') }}" class="font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">Bidang</a>.
                </span>
            </li>
            <li class="flex gap-2">
                <span aria-hidden="true" class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500"></span>
                <span>Catat kegiatan dan unggah proposalnya di
                    <a href="{{ route('admin.event.index') }}" class="font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">Event</a>.
                </span>
            </li>
            <li class="flex gap-2">
                <span aria-hidden="true" class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500"></span>
                <span>Bagi jadwal piket gerbang di
                    <a href="{{ route('admin.piket.index') }}" class="font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">Piket Gerbang</a>.
                </span>
            </li>
            <li class="flex gap-2">
                <span aria-hidden="true" class="mt-0.5 h-1.5 w-1.5 shrink-0 rounded-full bg-emas-500"></span>
                <span>Cari proposal lama di
                    <a href="{{ route('admin.arsip.index') }}" class="font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800">Arsip Proposal</a>.
                </span>
            </li>
        </ul>
    </div>
</x-admin-layout>
