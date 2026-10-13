@props(['title' => 'Panel Staf'])

@php
    $menu = [
        [
            'label' => 'Dashboard',
            'rute' => 'staf.dashboard',
            'aktif' => request()->routeIs('staf.*'),
            'ikon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75',
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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-tulang font-isi text-arang antialiased">
        <div x-data="{ sidebar: false }">

            {{-- Menu samping --}}
            <aside x-cloak
                   :class="sidebar ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col bg-laut-900 text-laut-100 transition-transform duration-200 ease-in-out lg:translate-x-0">
                <div class="flex h-16 shrink-0 items-center justify-between gap-3 border-b border-laut-800 px-4">
                    <a href="{{ route('staf.dashboard') }}" class="flex min-w-0 items-center gap-3">
                        <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon"
                             class="h-8 w-8 shrink-0 rounded-full border border-white/40 object-cover">
                        <span class="min-w-0 leading-tight">
                            <span class="block truncate font-judul text-base font-semibold tracking-wide text-white">Staf Batalyon</span>
                            <span class="block text-xs text-laut-300">SMKN 3 Pariaman &middot; Staf</span>
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
                           class="flex items-center gap-3 border-l-[3px] px-3 py-2 text-sm font-medium transition {{ $item['aktif'] ? 'border-emas-500 bg-laut-800 text-white' : 'border-transparent text-laut-300 hover:bg-laut-800 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['ikon'] }}" />
                            </svg>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="shrink-0 border-t border-laut-800 px-4 py-3 text-xs text-laut-300">
                    <p>Masuk sebagai</p>
                    <p class="mt-0.5 truncate font-medium text-white">{{ auth()->user()->name }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="shrink-0 border-t border-laut-800 px-2 py-2">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-laut-300 transition hover:bg-laut-800 hover:text-white">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </aside>

            {{-- Latar gelap saat menu dibuka di layar kecil --}}
            <div x-cloak x-show="sidebar" @click="sidebar = false"
                 class="fixed inset-0 z-30 bg-laut-900/60 lg:hidden"></div>

            <div class="lg:pl-64">
                <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-laut-100 bg-white px-4 sm:px-6">
                    <button type="button" @click="sidebar = true"
                            class="rounded-md p-2 text-arang/60 hover:bg-laut-50 hover:text-laut-800 lg:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <span class="sr-only">Buka menu</span>
                    </button>

                    <h1 class="font-judul text-lg font-semibold tracking-wide text-laut-900 sm:text-xl">{{ $title }}</h1>
                </header>

                <main class="px-4 py-6 sm:px-6">
                    @if (session('success'))
                        <div x-data="{ buka: true }" x-show="buka"
                             class="mb-4 flex items-start justify-between gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            <p>{{ session('success') }}</p>
                            <button type="button" @click="buka = false"
                                    class="shrink-0 rounded p-1 text-green-600 hover:bg-green-100" aria-label="Tutup">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ buka: true }" x-show="buka"
                             class="mb-4 flex items-start justify-between gap-3 rounded-lg border border-marun-200 bg-marun-50 px-4 py-3 text-sm text-marun-800">
                            <p>{{ session('error') }}</p>
                            <button type="button" @click="buka = false"
                                    class="shrink-0 rounded p-1 text-marun-600 hover:bg-marun-100" aria-label="Tutup">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
