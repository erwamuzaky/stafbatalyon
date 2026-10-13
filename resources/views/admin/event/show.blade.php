<x-admin-layout title="Detail Kegiatan">
    @php
        $badge = match ($event->status) {
            'rencana' => 'badge-rencana',
            'berjalan' => 'badge-berjalan',
            'selesai' => 'badge-selesai',
            default => 'badge-selesai',
        };
        $label = $statusPilihan[$event->status] ?? $event->status;
    @endphp

    {{-- Ringkasan kegiatan --}}
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="font-judul text-xl font-semibold text-laut-800">{{ $event->nama }}</h2>
                    <span class="badge {{ $badge }}">
                        {{ $label }}
                    </span>
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
                <a href="{{ route('admin.event.edit', $event) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                    Ubah Kegiatan
                </a>

                <a href="{{ route('admin.event.index') }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Unggah proposal baru --}}
    <div class="mt-4 rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h3 class="font-judul text-lg font-semibold text-laut-800">Unggah Proposal Baru</h3>
            <p class="mt-1 text-sm leading-relaxed text-gray-600">
                Berkas yang diterima: PDF, DOC, atau DOCX. Ukuran maksimal 10 MB.
            </p>

            <form method="POST" action="{{ route('admin.proposal.store', $event) }}"
                  enctype="multipart/form-data" class="mt-6 space-y-5">
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
                    <textarea id="catatan" name="catatan" rows="3"
                              class="input-baku mt-1 block w-full">{{ old('catatan') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar proposal --}}
    <div class="mt-4 overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-4 py-4 sm:px-6">
            <h3 class="font-judul text-lg font-semibold text-laut-800">
                Proposal Kegiatan ({{ $proposals->count() }})
            </h3>
            <p class="mt-0.5 text-xs text-gray-500">Unduh berkas atau pakai proposal lama sebagai dasar proposal baru.</p>
        </div>

        {{-- Tabel untuk layar sedang ke atas --}}
        <div class="hidden overflow-x-auto sm:block">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Catatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Berdasarkan</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-laut-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($proposals as $proposal)
                        <tr class="transition-colors hover:bg-laut-50/60">
                            <td class="px-4 py-3 text-sm">
                                <span class="font-medium text-arang">{{ $proposal->judul }}</span>
                                <span class="block text-xs text-gray-500">
                                    Diunggah {{ $proposal->created_at->format('d/m/Y H:i') }}
                                </span>
                                @if ($proposal->turunan_count > 0)
                                    <span class="badge badge-berjalan mt-1">
                                        Dipakai jadi dasar {{ $proposal->turunan_count }} proposal
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $proposal->catatan ?: '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                @if ($proposal->templateDari)
                                    Berdasarkan:
                                    <span class="font-medium text-arang">{{ $proposal->templateDari->judul }}</span>
                                    @if ($proposal->templateDari->event)
                                        <span class="block text-xs text-gray-500">
                                            dari kegiatan {{ $proposal->templateDari->event->nama }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    <a href="{{ route('admin.arsip.unduh', $proposal) }}"
                                       class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                        Unduh
                                    </a>

                                    <a href="{{ route('admin.proposal.dasar.create', $proposal) }}"
                                       class="btn btn-utama px-2.5 py-1.5 text-xs">
                                        Gunakan sebagai dasar
                                    </a>

                                    <form method="POST" action="{{ route('admin.proposal.destroy', $proposal) }}"
                                          onsubmit="return confirm('Hapus proposal {{ $proposal->judul }}? Berkasnya ikut terhapus.')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"
                                                class="btn btn-bahaya px-2.5 py-1.5 text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                Belum ada proposal untuk kegiatan ini.
                                <span class="mt-1 block text-xs">Unggah proposal pertama lewat form di atas.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Kartu untuk layar HP --}}
        <div class="space-y-3 p-4 sm:hidden">
            @forelse ($proposals as $proposal)
                <div class="rounded-lg border border-laut-100 bg-white p-3 shadow-sm">
                    <p class="text-sm font-semibold text-arang">{{ $proposal->judul }}</p>
                    <p class="mt-0.5 text-xs text-gray-500">Diunggah {{ $proposal->created_at->format('d/m/Y H:i') }}</p>
                    @if ($proposal->turunan_count > 0)
                        <span class="badge badge-berjalan mt-1">Dipakai jadi dasar {{ $proposal->turunan_count }} proposal</span>
                    @endif
                    <p class="mt-2 text-xs text-gray-600">Catatan: <span class="text-arang">{{ $proposal->catatan ?: '-' }}</span></p>
                    <p class="mt-1 text-xs text-gray-600">
                        @if ($proposal->templateDari)
                            Berdasarkan: <span class="font-medium text-arang">{{ $proposal->templateDari->judul }}</span>
                        @else
                            Belum berdasarkan proposal lain.
                        @endif
                    </p>
                    <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-100 pt-3">
                        <a href="{{ route('admin.arsip.unduh', $proposal) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Unduh</a>
                        <a href="{{ route('admin.proposal.dasar.create', $proposal) }}" class="btn btn-utama px-2.5 py-1.5 text-xs">Gunakan sebagai dasar</a>
                        <form method="POST" action="{{ route('admin.proposal.destroy', $proposal) }}"
                              onsubmit="return confirm('Hapus proposal {{ $proposal->judul }}? Berkasnya ikut terhapus.')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-bahaya px-2.5 py-1.5 text-xs">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-dashed border-laut-200 bg-laut-50/50 p-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada proposal untuk kegiatan ini.</p>
                    <p class="mt-1 text-xs text-gray-400">Unggah proposal pertama lewat form di atas.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
