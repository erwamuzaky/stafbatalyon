<x-admin-layout title="Detail Kegiatan">
    {{-- Ringkasan kegiatan --}}
    <x-card>
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="font-judul text-xl font-semibold text-laut-800">{{ $event->nama }}</h2>
                    <x-badge :status="$event->status" :label="$statusPilihan[$event->status] ?? $event->status" />
                </div>

                <dl class="mt-3 grid gap-x-10 gap-y-2 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-gray-500">Tahun</dt>
                        <dd class="font-medium text-arang">{{ $event->tahun }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Tanggal</dt>
                        <dd class="font-medium text-arang">
                            {{ $event->tanggal_mulai->format('d/m/Y') }}
                            @if ($event->tanggal_selesai)
                                s.d. {{ $event->tanggal_selesai->format('d/m/Y') }}
                            @else
                                <span class="font-normal text-gray-500">(satu hari)</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.event.edit', $event) }}" class="btn btn-sekunder">
                    <x-icon name="ubah" class="h-4 w-4" />
                    Ubah kegiatan
                </a>
                <a href="{{ route('admin.event.index') }}" class="btn btn-sekunder">Kembali</a>
            </div>
        </div>
    </x-card>

    {{-- Unggah proposal baru --}}
    <x-card title="Unggah Proposal Baru" class="mt-4">
        <div class="max-w-xl">
            <p class="text-sm text-gray-600">
                Berkas yang diterima: PDF, DOC, atau DOCX. Ukuran maksimal 10 MB.
            </p>

            <form method="POST" action="{{ route('admin.proposal.store', $event) }}"
                  enctype="multipart/form-data" class="mt-5 space-y-5"
                  x-data="{ memproses: false }" @submit="memproses = true">
                @csrf

                <div>
                    <x-input-label for="judul" value="Judul Proposal" />
                    <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full"
                                  :value="old('judul')" required placeholder="Contoh: Proposal Pentas Seni 2026" />
                    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="file" value="Berkas Proposal" />
                    <input id="file" name="file" type="file" required
                           accept=".pdf,.doc,.docx"
                           class="input-baku mt-1 block w-full p-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-laut-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-laut-700">
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan (opsional)" />
                    <textarea id="catatan" name="catatan" rows="3" class="input-baku mt-1 block w-full">{{ old('catatan') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>

                <div class="pt-1">
                    <x-primary-button x-bind:disabled="memproses">
                        <span x-show="!memproses">Simpan proposal</span>
                        <span x-show="memproses" x-cloak>Menyimpan...</span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Daftar proposal --}}
    <x-card class="mt-4">
        <x-slot name="header">
            <h3 class="font-judul text-lg font-semibold text-laut-800">
                Proposal Kegiatan ({{ $proposals->count() }})
            </h3>
            <p class="mt-0.5 text-xs text-gray-500">Unduh berkas atau pakai proposal lama sebagai dasar proposal baru.</p>
        </x-slot>

        <x-tabel-responsif :headers="['Judul', 'Catatan', 'Berdasarkan', 'Aksi']">
            @forelse ($proposals as $proposal)
                <tr class="transition-colors hover:bg-laut-50/50">
                    <td class="px-4 py-3">
                        <span class="font-medium text-arang">{{ $proposal->judul }}</span>
                        <span class="block text-xs text-gray-500">Diunggah {{ $proposal->created_at->format('d/m/Y H:i') }}</span>
                        @if ($proposal->turunan_count > 0)
                            <span class="mt-1 inline-block">
                                <x-badge status="berjalan" :label="'Jadi dasar '.$proposal->turunan_count.' proposal'" />
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $proposal->catatan ?: '-' }}</td>
                    <td class="px-4 py-3 text-gray-700">
                        @if ($proposal->templateDari)
                            <span class="font-medium text-arang">{{ $proposal->templateDari->judul }}</span>
                            @if ($proposal->templateDari->event)
                                <span class="block text-xs text-gray-500">dari kegiatan {{ $proposal->templateDari->event->nama }}</span>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <x-aksi :href="route('admin.arsip.unduh', $proposal)" ikon="unduh" label="Unduh proposal" />
                            <x-aksi :href="route('admin.proposal.dasar.create', $proposal)" ikon="bintang" label="Gunakan sebagai dasar" />
                            <x-konfirmasi-hapus :action="route('admin.proposal.destroy', $proposal)"
                                                :judul="'proposal '.$proposal->judul"
                                                pesan="Berkasnya ikut terhapus."
                                                teks="Hapus proposal" ikon="hapus" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-10">
                        <x-empty title="Belum ada proposal" ikon="dokumen">
                            Unggah proposal pertama lewat form di atas.
                        </x-empty>
                    </td>
                </tr>
            @endforelse

            <x-slot name="mobile">
                @forelse ($proposals as $proposal)
                    <div class="rounded-xl border border-laut-100 p-4">
                        <p class="text-sm font-semibold text-arang">{{ $proposal->judul }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">Diunggah {{ $proposal->created_at->format('d/m/Y H:i') }}</p>
                        @if ($proposal->turunan_count > 0)
                            <span class="mt-1 inline-block">
                                <x-badge status="berjalan" :label="'Jadi dasar '.$proposal->turunan_count.' proposal'" />
                            </span>
                        @endif
                        <p class="mt-2 text-xs text-gray-600">Catatan: <span class="text-arang">{{ $proposal->catatan ?: '-' }}</span></p>
                        <p class="mt-1 text-xs text-gray-600">
                            @if ($proposal->templateDari)
                                Berdasarkan: <span class="font-medium text-arang">{{ $proposal->templateDari->judul }}</span>
                            @else
                                Belum berdasarkan proposal lain.
                            @endif
                        </p>
                        <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3">
                            <x-aksi :href="route('admin.arsip.unduh', $proposal)" ikon="unduh" label="Unduh proposal" />
                            <x-aksi :href="route('admin.proposal.dasar.create', $proposal)" ikon="bintang" label="Gunakan sebagai dasar" />
                            <x-konfirmasi-hapus :action="route('admin.proposal.destroy', $proposal)"
                                                :judul="'proposal '.$proposal->judul"
                                                pesan="Berkasnya ikut terhapus."
                                                teks="Hapus proposal" ikon="hapus" />
                        </div>
                    </div>
                @empty
                    <x-empty title="Belum ada proposal" ikon="dokumen">
                        Unggah proposal pertama lewat form di atas.
                    </x-empty>
                @endforelse
            </x-slot>
        </x-tabel-responsif>
    </x-card>
</x-admin-layout>
