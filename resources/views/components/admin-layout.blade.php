@props(['title' => 'Panel Admin'])

@php
    $menu = [
        [
            'label' => 'Dashboard',
            'rute' => 'admin.dashboard',
            'aktif' => request()->routeIs('admin.dashboard'),
            'ikon' => 'dashboard',
        ],
        [
            'label' => 'Kelola Akun',
            'rute' => 'admin.akun.index',
            'aktif' => request()->routeIs('admin.akun.*'),
            'ikon' => 'akun',
        ],
        [
            'label' => 'Data Staf',
            'rute' => 'admin.staf.index',
            'aktif' => request()->routeIs('admin.staf.*'),
            'ikon' => 'staf',
        ],
        [
            'label' => 'Bagan Struktur',
            'rute' => 'admin.bagan.index',
            'aktif' => request()->routeIs('admin.bagan.*'),
            'ikon' => 'bidang',
        ],
        [
            'label' => 'Bidang',
            'rute' => 'admin.bidang.index',
            'aktif' => request()->routeIs('admin.bidang.*'),
            'ikon' => 'bidang',
        ],
        [
            'label' => 'Event',
            'rute' => 'admin.event.index',
            'aktif' => request()->routeIs('admin.event.*'),
            'ikon' => 'event',
        ],
        [
            'label' => 'Piket Gerbang',
            'rute' => 'admin.piket.index',
            'aktif' => request()->routeIs('admin.piket.*'),
            'ikon' => 'piket',
        ],
        [
            'label' => 'Arsip Proposal',
            'rute' => 'admin.arsip.index',
            'aktif' => request()->routeIs('admin.arsip.*'),
            'ikon' => 'dokumen',
        ],
        [
            'label' => 'Cetak Absensi',
            'rute' => 'admin.absensi.create',
            'aktif' => request()->routeIs('admin.absensi.*'),
            'ikon' => 'cetak',
        ],
        [
            'label' => 'Pengaturan',
            'rute' => 'admin.pengaturan.index',
            'aktif' => request()->routeIs('admin.pengaturan.*'),
            'ikon' => 'pengaturan',
        ],
        [
            'label' => 'Profil Saya',
            'rute' => 'profile.edit',
            'aktif' => request()->routeIs('profile.*'),
            'ikon' => 'profil',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }} - {{ config('app.name', 'Staf Batalyon') }}</title>

        <link rel="icon" href="{{ asset('images/logo-staf-batalyon.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-tulang font-isi text-arang antialiased">
        <div x-data="{ sidebar: false }">

            {{-- Menu samping --}}
            <aside x-cloak
                   :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col bg-white border-r border-gray-100 transition-transform duration-200 ease-in-out lg:translate-x-0">
                <div class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-gray-100 px-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3">
                        <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon"
                             class="h-8 w-8 shrink-0 rounded-full border border-gray-100 object-cover">
                        <span class="min-w-0 leading-tight">
                            <span class="block truncate font-judul text-base font-semibold tracking-wide text-gray-900">Staf Batalyon</span>
                            <span class="block text-xs text-gray-500">SMKN 3 Pariaman &middot; Admin</span>
                        </span>
                    </a>

                    <button type="button" @click="sidebar = false"
                            class="rounded-md p-1 text-laut-300 hover:bg-laut-800 hover:text-white lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="sr-only">Tutup menu</span>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-2 py-4">
                    @foreach ($menu as $item)
                        <a href="{{ route($item['rute']) }}"
                           class="group relative flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition {{ $item['aktif'] ? 'bg-laut-50 text-laut-800' : 'text-gray-600 hover:bg-gray-50' }}">
                            @if ($item['aktif'])
                                <span class="absolute left-0 h-8 w-1 rounded-r-full bg-emas-500"></span>
                            @endif
                            <x-icon :name="$item['ikon']" class="h-5 w-5 {{ $item['aktif'] ? 'text-laut-800' : 'text-gray-400 group-hover:text-gray-500' }}" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="shrink-0 border-t border-gray-100 p-4">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="auth()->user()->name" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="shrink-0 border-t border-gray-100 p-2">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-marun-50 hover:text-marun-700">
                        <x-icon name="keluar" class="h-5 w-5 shrink-0 text-gray-400" />
                        Keluar
                    </button>
                </form>
            </aside>

            {{-- Latar gelap saat menu dibuka di layar kecil --}}
            <div x-cloak x-show="sidebar" @click="sidebar = false"
                 class="fixed inset-0 z-30 bg-laut-900/60 lg:hidden"></div>

            <div class="lg:pl-64">
                <header class="sticky top-0 z-20 flex min-h-16 items-center gap-3 border-b border-laut-100 bg-white px-4 py-3 sm:px-6">
                    <button type="button" @click="sidebar = true"
                            class="-ml-1 rounded-md p-2 text-arang/60 hover:bg-laut-50 hover:text-laut-800 lg:hidden">
                        <x-icon name="menu" class="h-6 w-6" />
                        <span class="sr-only">Buka menu</span>
                    </button>

                    <x-page-header :title="$title" class="min-w-0 flex-1" />
                </header>

                <main id="konten-utama" class="px-4 py-6 sm:px-6">
                    @if (session('success'))
                        <x-alert type="sukses" :auto-dismiss="5000" class="mb-4">{{ session('success') }}</x-alert>
                    @endif

                    @if (session('error'))
                        <x-alert type="error" class="mb-4">{{ session('error') }}</x-alert>
                    @endif

                    @if (session('password_baru'))
                        <x-alert type="peringatan" class="mb-4">
                            <p class="font-semibold">Kata sandi baru sudah dibuat</p>
                            <p class="mt-1">
                                Akun: {{ session('password_baru')['nama'] }}
                                ({{ session('password_baru')['email'] }})
                            </p>
                            <p class="mt-1">
                                Kata sandi baru:
                                <span class="ml-1 rounded bg-white px-2 py-0.5 font-mono text-base font-semibold tracking-wide">{{ session('password_baru')['password'] }}</span>
                            </p>
                            <p class="mt-2 text-xs">
                                Catat dan berikan ke yang bersangkutan sekarang. Kata sandi ini hanya muncul satu kali.
                            </p>
                        </x-alert>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
