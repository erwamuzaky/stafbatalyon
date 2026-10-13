<x-admin-layout title="Detail Kegiatan">
    @php
        $badge = match ($event->status) {
            'rencana' => 'bg-amber-100 text-amber-800',
            'berjalan' => 'bg-indigo-100 text-indigo-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-700',
        };
        $label = $statusPilihan[$event->status] ?? $event->status;
    @endphp

    {{-- Ringkasan kegiatan --}}
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $event->nama }}</h2>
                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">
                        {{ $label }}
                    </span>
                </div>

                <dl class="mt-3 grid gap-x-10 gap-y-2 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-gray-500">Tahun</dt>
                        <dd class="font-medium text-gray-800">{{ $event->tahun }}</dd>
                    </div>

                    <div>
                        <dt class="text-gray-500">Tanggal</dt>
                        <dd class="font-medium text-gray-800">
                            {{ $event->tanggal_mulai->format('d/m/Y') }}
                            @if ($event->tanggal_selesai)
                                s.d. {{ $event->tanggal_selesai->format('d/m/Y') }}
                            @else
                                <span class="text-gray-500">(satu hari)</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.event.edit', $event) }}"
                   class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                    Ubah Kegiatan
                </a>

                <a href="{{ route('admin.event.index') }}"
                   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Unggah proposal baru --}}
    <div class="mt-6 rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <h3 class="text-base font-semibold text-gray-800">Unggah Proposal Baru</h3>
        <p class="mt-1 text-sm text-gray-600">
            Berkas yang diterima: PDF, DOC, atau DOCX. Ukuran maksimal 10 MB.
        </p>

        <form method="POST" action="{{ route('admin.proposal.store', $event) }}"
              enctype="multipart/form-data" class="mt-6 max-w-xl space-y-5">
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
                       class="mt-1 block w-full rounded-md border border-gray-300 p-2 text-sm text-gray-700 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-gray-800 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:uppercase file:tracking-widest file:text-white">
                <x-input-error :messages="$errors->get('file')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="catatan" value="Catatan (opsional)" />
                <textarea id="catatan" name="catatan" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="pt-2">
                <x-primary-button>Unggah Proposal</x-primary-button>
            </div>
        </form>
    </div>

    {{-- Daftar proposal --}}
    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
            <h3 class="text-base font-semibold text-gray-800">
                Proposal Kegiatan ({{ $proposals->count() }})
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Catatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Berdasarkan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($proposals as $proposal)
                        <tr>
                            <td class="px-4 py-3 text-sm">
                                <span class="font-medium text-gray-900">{{ $proposal->judul }}</span>
                                <span class="block text-xs text-gray-500">
                                    Diunggah {{ $proposal->created_at->format('d/m/Y H:i') }}
                                </span>
                                @if ($proposal->turunan_count > 0)
                                    <span class="mt-1 inline-flex rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">
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
                                    <span class="font-medium text-gray-900">{{ $proposal->templateDari->judul }}</span>
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
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('admin.arsip.unduh', $proposal) }}"
                                       class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Unduh
                                    </a>

                                    <a href="{{ route('admin.proposal.dasar.create', $proposal) }}"
                                       class="inline-flex items-center rounded-md border border-indigo-300 px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50">
                                        Gunakan sebagai dasar
                                    </a>

                                    <form method="POST" action="{{ route('admin.proposal.destroy', $proposal) }}"
                                          onsubmit="return confirm('Hapus proposal {{ $proposal->judul }}? Berkasnya ikut terhapus.')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md border border-red-300 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada proposal untuk kegiatan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
