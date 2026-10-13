<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Staf Batalyon - SMKN 3 Pariaman</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans text-gray-900 antialiased">
        {{-- Navbar --}}
        <nav class="sticky top-0 z-20 border-b border-gray-200 bg-white">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-sm font-bold text-gray-800 sm:text-base">Staf Batalyon</span>
                        <span class="block text-xs text-gray-500">SMKN 3 Pariaman</span>
                    </span>
                </a>

                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 sm:px-4">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 sm:px-4">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 sm:px-4">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- Hero --}}
        <header class="bg-indigo-700">
            <div class="relative mx-auto max-w-6xl overflow-hidden px-4 py-12 sm:px-6 sm:py-16">
                <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-indigo-600" aria-hidden="true"></div>
                <div class="pointer-events-none absolute -bottom-20 right-24 h-40 w-40 rounded-full bg-indigo-500 opacity-60" aria-hidden="true"></div>

                <div class="relative max-w-2xl">
                    <p class="inline-flex rounded-full bg-indigo-800 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-indigo-200">
                        Organisasi Kesiswaan
                    </p>
                    <h1 class="mt-4 text-3xl font-extrabold text-white sm:text-4xl">Staf Batalyon</h1>
                    <p class="mt-1 text-lg font-semibold text-indigo-200">SMKN 3 Pariaman</p>
                    <p class="mt-4 text-sm leading-relaxed text-indigo-100 sm:text-base">
                        Staf Batalyon adalah organisasi kesiswaan setara OSIS yang membantu
                        kelancaran kegiatan sekolah, mulai dari ketertiban harian sampai
                        pelaksanaan acara dan kepanitiaan.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                               class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                                Daftar
                            </a>
                            <a href="{{ route('login') }}"
                               class="rounded-md border border-indigo-300 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 sm:py-10">
            {{-- Struktur staf inti --}}
            <section>
                <h2 class="text-base font-semibold text-gray-800 sm:text-lg">Struktur Staf</h2>
                <p class="mt-1 text-sm text-gray-600">Staf inti yang memimpin kepengurusan periode berjalan.</p>

                @if ($stafInti->isNotEmpty())
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($stafInti as $user)
                            <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-lg font-bold text-indigo-700">
                                        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                                        <p class="truncate text-sm text-gray-600">{{ $user->stafProfile?->jabatan ?? '-' }}</p>
                                    </div>
                                </div>
                                <p class="mt-3 inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                                    {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mt-4 rounded-lg bg-white p-4 text-sm text-gray-500 shadow-sm sm:p-6">
                        Belum ada data staf inti.
                    </div>
                @endif
            </section>

            {{-- Daftar bidang --}}
            <section class="mt-8">
                <h2 class="text-base font-semibold text-gray-800 sm:text-lg">Bidang</h2>
                <p class="mt-1 text-sm text-gray-600">Bidang-bidang yang menaungi seluruh staf batalyon.</p>

                @if ($bidang->isNotEmpty())
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($bidang as $item)
                            <span class="inline-flex items-center gap-2 rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-medium text-indigo-700 shadow-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                {{ $item->nama }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="mt-4 rounded-lg bg-white p-4 text-sm text-gray-500 shadow-sm sm:p-6">
                        Belum ada data bidang.
                    </div>
                @endif
            </section>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-6 text-center text-sm text-gray-500 sm:px-6">
                Staf Batalyon SMKN 3 Pariaman &middot; &copy; {{ date('Y') }}
            </div>
        </footer>
    </body>
</html>
