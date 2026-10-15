@props(['pengaturan'])

{{-- Footer untuk halaman publik --}}
<footer class="bg-laut-900 text-white">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white p-0.5">
                    <img src="{{ asset('images/logo-staf-batalyon.jpg') }}" alt="Logo Staf Batalyon" class="h-9 w-9 rounded-full object-cover">
                </span>
                <div>
                    <p class="font-judul font-semibold">{{ $pengaturan['footer_nama'] }}</p>
                    <p class="text-sm text-laut-100">{{ $pengaturan['footer_sekolah'] }}</p>
                </div>
            </div>

            <div class="w-full sm:w-72">
                <div class="relative overflow-hidden rounded-xl border border-white/10 bg-laut-800">
                    <iframe
                        src="https://maps.google.com/maps?q={{ urlencode($pengaturan['footer_peta_kata_kunci']) }}&amp;z=16&amp;output=embed"
                        class="pointer-events-none h-40 w-full"
                        loading="lazy"
                        title="Peta lokasi {{ $pengaturan['footer_sekolah'] }}"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <a href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($pengaturan['footer_peta_kata_kunci']) }}"
                        target="_blank" rel="noopener noreferrer"
                        aria-label="Buka lokasi {{ $pengaturan['footer_sekolah'] }} di Google Maps pada tab baru"
                        class="absolute inset-0 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-laut-900"></a>
                </div>
                <p class="mt-2 text-xs text-laut-100/70">{{ $pengaturan['footer_peta_catatan'] }}</p>
            </div>
        </div>

        <p class="mt-8 border-t border-white/10 pt-6 text-sm text-laut-100">
            {{ $pengaturan['footer_hak_cipta'] }} &middot; &copy; {{ date('Y') }}
        </p>
    </div>
</footer>
