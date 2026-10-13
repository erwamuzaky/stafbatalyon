@props(['title' => 'Panel Admin'])

@php
    $menu = [
        [
            'label' => 'Dashboard',
            'rute' => 'admin.dashboard',
            'aktif' => request()->routeIs('admin.dashboard'),
            'ikon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75',
        ],
        [
            'label' => 'Kelola Akun',
            'rute' => 'admin.akun.index',
            'aktif' => request()->routeIs('admin.akun.*'),
            'ikon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        ],
        [
            'label' => 'Data Staf',
            'rute' => 'admin.staf.index',
            'aktif' => request()->routeIs('admin.staf.*'),
            'ikon' => 'M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184',
        ],
        [
            'label' => 'Bidang',
            'rute' => 'admin.bidang.index',
            'aktif' => request()->routeIs('admin.bidang.*'),
            'ikon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
        ],
        [
            'label' => 'Event',
            'rute' => 'admin.event.index',
            'aktif' => request()->routeIs('admin.event.*'),
            'ikon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
        ],
        [
            'label' => 'Piket Gerbang',
            'rute' => 'admin.piket.index',
            'aktif' => request()->routeIs('admin.piket.*'),
            'ikon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'Arsip Proposal',
            'rute' => 'admin.arsip.index',
            'aktif' => request()->routeIs('admin.arsip.*'),
            'ikon' => 'M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z',
        ],
        [
            'label' => 'Cetak Absensi',
            'rute' => 'admin.absensi.create',
            'aktif' => request()->routeIs('admin.absensi.*'),
            'ikon' => 'M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z',
        ],
        [
            'label' => 'Profil Saya',
            'rute' => 'profile.edit',
            'aktif' => request()->routeIs('profile.*'),
            'ikon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z',
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

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div x-data="{ sidebar: false }">

            {{-- Menu samping --}}
            <aside x-cloak
                   :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col bg-gray-800 text-gray-100 transition-transform duration-200 ease-in-out lg:translate-x-0">
                <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-700 px-4">
                    <a href="{{ route('admin.dashboard') }}" class="leading-tight">
                        <span class="block text-sm font-semibold text-white">Staf Batalyon</span>
                        <span class="block text-xs text-gray-400">SMKN 3 Pariaman &middot; Admin</span>
                    </a>

                    <button type="button" @click="sidebar = false"
                            class="rounded-md p-1 text-gray-400 hover:text-white lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="sr-only">Tutup menu</span>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-2 py-4">
                    @foreach ($menu as $item)
                        <a href="{{ route($item['rute']) }}"
                           class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition {{ $item['aktif'] ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['ikon'] }}" />
                            </svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="shrink-0 border-t border-gray-700 px-4 py-3 text-xs text-gray-400">
                    <p>Masuk sebagai</p>
                    <p class="mt-0.5 truncate font-medium text-gray-100">{{ auth()->user()->name }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="shrink-0 border-t border-gray-700 px-2 py-2">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </aside>

            {{-- Latar gelap saat menu dibuka di layar kecil --}}
            <div x-cloak x-show="sidebar" @click="sidebar = false"
                 class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"></div>

            <div class="lg:pl-64">
                <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-gray-200 bg-white px-4 sm:px-6">
                    <button type="button" @click="sidebar = true"
                            class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <span class="sr-only">Buka menu</span>
                    </button>

                    <h1 class="text-base font-semibold text-gray-800 sm:text-lg">{{ $title }}</h1>
                </header>

                <main class="px-4 py-6 sm:px-6">
                    @if (session('success'))
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('password_baru'))
                        <div class="mb-4 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
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
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
