@props(['pengaturan'])

{{-- Navbar mengambang lembut untuk halaman publik --}}
<div class="sticky top-0 z-30 px-3 pt-3 sm:px-4 sm:pt-4">
    <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 rounded-2xl border border-laut-100 bg-white/95 px-3 py-2 shadow-soft-sm backdrop-blur supports-[backdrop-filter]:bg-white/85 sm:px-4">
        <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-2.5">
            <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-9 w-9 shrink-0 rounded-full border border-laut-100 object-cover">
            <span class="min-w-0 leading-tight">
                <span class="block truncate font-judul text-base font-bold text-arang">{{ $pengaturan['situs_nama'] }}</span>
                <span class="hidden text-xs text-arang/70 sm:block">{{ $pengaturan['situs_sekolah'] }}</span>
            </span>
        </a>

        <div class="flex shrink-0 items-center gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-utama">Buka Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sekunder">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-utama">Daftar</a>
            @endauth
        </div>
    </nav>
</div>
