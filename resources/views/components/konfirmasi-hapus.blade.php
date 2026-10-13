@props([
    'action',
    'judul',
    'pesan' => null,
    'teks' => 'Hapus',
])

{{--
    Tombol hapus dengan dialog konfirmasi.

    Pemakaian:
    <x-konfirmasi-hapus :action="route('...', $data)" judul="kegiatan Contoh"
                        pesan="Semua proposal di kegiatan ini ikut terhapus." />
--}}
<div x-data="{ buka: false }" class="inline-block">
    <button type="button" @click="buka = true"
            class="inline-flex items-center rounded-md border border-red-300 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
        {{ $teks }}
    </button>

    <div x-cloak x-show="buka" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50" @click="buka = false"></div>

        <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl" role="dialog" aria-modal="true">
            <h3 class="text-base font-semibold text-gray-900">Hapus {{ $judul }}?</h3>

            <p class="mt-2 text-sm text-gray-600">
                Tindakan ini tidak bisa dibatalkan.
            </p>

            @if ($pesan)
                <p class="mt-2 rounded-md bg-red-50 px-3 py-2 text-sm text-red-800">
                    {{ $pesan }}
                </p>
            @endif

            <form method="POST" action="{{ $action }}" class="mt-6 flex justify-end gap-3">
                @csrf
                @method('delete')

                <button type="button" @click="buka = false"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                    Batal
                </button>

                <button type="submit"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-red-500">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
