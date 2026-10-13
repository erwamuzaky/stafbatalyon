@props([
    'action',
    'judul',
    'pesan' => null,
    'teks' => 'Hapus',
    'ikon' => null,
])

{{--
    Tombol hapus dengan dialog konfirmasi.

    Pemakaian:
    <x-konfirmasi-hapus :action="route('...', $data)" judul="kegiatan Contoh"
                        pesan="Semua proposal di kegiatan ini ikut terhapus." />

    Untuk tabel rapat, kirim ikon="hapus" supaya tombolnya jadi ikon dengan tooltip.
--}}
<div x-data="{ buka: false }" class="inline-block">
    @if ($ikon)
        <button type="button" @click="buka = true"
                class="ikon-aksi ikon-aksi-bahaya" title="{{ $teks }}" aria-label="{{ $teks }}">
            <x-icon :name="$ikon" />
        </button>
    @else
        <button type="button" @click="buka = true" class="btn btn-bahaya">
            {{ $teks }}
        </button>
    @endif

    <div x-cloak x-show="buka" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-arang/60" @click="buka = false"></div>

        <div class="relative w-full max-w-md rounded-2xl border-t-4 border-marun-500 bg-white p-6 shadow-soft-lg" role="dialog" aria-modal="true">
            <h3 class="font-judul text-lg font-semibold text-arang">Hapus {{ $judul }}?</h3>

            <p class="mt-2 text-sm text-arang/80">
                Tindakan ini tidak bisa dibatalkan.
            </p>

            @if ($pesan)
                <p class="mt-3 rounded-md bg-marun-50 px-3 py-2 text-sm text-marun-600">
                    {{ $pesan }}
                </p>
            @endif

            <form method="POST" action="{{ $action }}" class="mt-6 flex justify-end gap-3">
                @csrf
                @method('delete')

                <button type="button" @click="buka = false" class="btn btn-sekunder">
                    Batal
                </button>

                <button type="submit" class="btn btn-bahaya">
                    Ya, hapus
                </button>
            </form>
        </div>
    </div>
</div>
