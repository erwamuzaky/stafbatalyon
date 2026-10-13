<x-admin-layout title="Dashboard">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Staf aktif --}}
        <div class="rounded-lg bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Staf Aktif</p>
            <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $jumlahStaf }}</p>
            <p class="mt-1 text-xs text-gray-500">Staf biasa yang sudah disetujui admin.</p>
        </div>

        {{-- Pendaftar menunggu --}}
        <a href="{{ route('admin.akun.index', ['status' => 'pending']) }}"
           class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-transparent transition hover:ring-indigo-300">
            <p class="text-sm font-medium text-gray-500">Menunggu Persetujuan</p>
            <p class="mt-2 text-3xl font-semibold {{ $jumlahPending > 0 ? 'text-amber-600' : 'text-gray-800' }}">
                {{ $jumlahPending }}
            </p>
            <p class="mt-1 text-xs font-medium text-indigo-600">
                Buka Kelola Akun untuk memeriksa pendaftar &rarr;
            </p>
        </a>

        {{-- Event --}}
        <a href="{{ route('admin.event.index') }}"
           class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-transparent transition hover:ring-indigo-300">
            <p class="text-sm font-medium text-gray-500">Kegiatan (Event)</p>
            <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $jumlahEvent }}</p>
            <p class="mt-1 text-xs font-medium text-indigo-600">
                Lihat daftar kegiatan &rarr;
            </p>
        </a>

        {{-- Piket minggu ini --}}
        <a href="{{ route('admin.piket.index') }}"
           class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-transparent transition hover:ring-indigo-300">
            <p class="text-sm font-medium text-gray-500">Piket Minggu Ini</p>
            <p class="mt-2 text-3xl font-semibold text-gray-800">{{ $jumlahPiketMingguIni }}</p>
            <p class="mt-1 text-xs font-medium text-indigo-600">
                Atur pembagian piket gerbang &rarr;
            </p>
        </a>
    </div>

    <div class="mt-6 rounded-lg bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800">Selamat datang, {{ auth()->user()->name }}</h2>
        <p class="mt-2 text-sm text-gray-600">
            Dari halaman ini Anda bisa mengelola akun staf, data staf, dan daftar bidang.
        </p>

        <ul class="mt-4 space-y-1 text-sm text-gray-600">
            <li>
                &bull; Periksa pendaftar baru di
                <a href="{{ route('admin.akun.index') }}" class="font-medium text-indigo-600 hover:underline">Kelola Akun</a>.
            </li>
            <li>
                &bull; Ubah data staf di
                <a href="{{ route('admin.staf.index') }}" class="font-medium text-indigo-600 hover:underline">Data Staf</a>.
            </li>
            <li>
                &bull; Tambah atau ubah bidang di
                <a href="{{ route('admin.bidang.index') }}" class="font-medium text-indigo-600 hover:underline">Bidang</a>.
            </li>
            <li>
                &bull; Catat kegiatan dan unggah proposalnya di
                <a href="{{ route('admin.event.index') }}" class="font-medium text-indigo-600 hover:underline">Event</a>.
            </li>
            <li>
                &bull; Bagi jadwal piket gerbang di
                <a href="{{ route('admin.piket.index') }}" class="font-medium text-indigo-600 hover:underline">Piket Gerbang</a>.
            </li>
            <li>
                &bull; Cari proposal lama di
                <a href="{{ route('admin.arsip.index') }}" class="font-medium text-indigo-600 hover:underline">Arsip Proposal</a>.
            </li>
        </ul>
    </div>
</x-admin-layout>
