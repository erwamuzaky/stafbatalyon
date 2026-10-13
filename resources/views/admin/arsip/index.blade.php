<x-admin-layout title="Arsip Proposal">
    <x-card>
        <x-slot name="header">
            <h3 class="font-judul text-lg font-semibold text-laut-800">Arsip Proposal</h3>
            <p class="mt-1 text-sm text-gray-600">
                Semua proposal dari seluruh kegiatan. Gunakan pencarian dan filter tahun untuk menemukannya.
            </p>
        </x-slot>

        <form method="GET" action="{{ route('admin.arsip.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-72"
                              :value="$q" placeholder="Cari judul proposal atau nama kegiatan" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="tahun" value="Tahun" />
                <select id="tahun" name="tahun" class="input-baku mt-1 block w-full sm:w-40">
                    <option value="">Semua tahun</option>
                    @foreach ($tahunPilihan as $pilihan)
                        <option value="{{ $pilihan }}" @selected((string) $tahun === (string) $pilihan)>
                            {{ $pilihan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Cari</x-primary-button>

            @if (($q ?? '') !== '' || ($tahun ?? '') !== '')
                <a href="{{ route('admin.arsip.index') }}"
                   class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $proposals->total() }} proposal.</p>
    </x-card>

    @if ($proposals->isNotEmpty())
        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($proposals as $proposal)
                @php
                    $ext = strtoupper(pathinfo($proposal->file_path, PATHINFO_EXTENSION)) ?: 'FILE';
                    $tipe = match ($ext) {
                        'PDF' => ['bg-bahaya-50 text-bahaya-700', 'dokumen'],
                        'DOC', 'DOCX' => ['bg-info-50 text-info-700', 'dokumen'],
                        default => ['bg-laut-50 text-laut-700', 'dokumen'],
                    };
                @endphp
                <div class="flex flex-col rounded-2xl border border-laut-100 bg-white p-5 shadow-soft-sm">
                    <div class="flex items-start gap-3">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tipe[0] }}">
                            <x-icon name="dokumen" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-arang" title="{{ $proposal->judul }}">{{ $proposal->judul }}</p>
                            <p class="mt-0.5 text-xs text-gray-500">
                                @if ($proposal->event)
                                    <a href="{{ route('admin.event.show', $proposal->event) }}"
                                       class="text-laut-700 underline-offset-2 hover:text-laut-800 hover:underline">{{ $proposal->event->nama }}</a>
                                    &middot; {{ $proposal->event->tahun }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <span class="shrink-0 rounded-md bg-laut-50 px-2 py-0.5 text-[11px] font-semibold text-laut-700">{{ $ext }}</span>
                    </div>

                    @if ($proposal->catatan)
                        <p class="mt-3 line-clamp-2 text-xs text-gray-600">{{ $proposal->catatan }}</p>
                    @endif

                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                        <span>Diunggah {{ $proposal->created_at->format('d/m/Y') }}</span>
                        @if ($proposal->templateDari)
                            <span class="text-gray-400">&middot;</span>
                            <span>Dasar: {{ $proposal->templateDari->judul }}</span>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center gap-2 border-t border-gray-100 pt-3">
                        <a href="{{ route('admin.arsip.unduh', $proposal) }}" class="btn btn-sekunder flex-1 text-xs">
                            <x-icon name="unduh" class="h-4 w-4" />
                            Unduh
                        </a>
                        <a href="{{ route('admin.proposal.dasar.create', $proposal) }}"
                           class="ikon-aksi" title="Gunakan sebagai dasar" aria-label="Gunakan sebagai dasar">
                            <x-icon name="bintang" />
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="mt-4">
            <x-empty title="Belum ada proposal" ikon="dokumen">
                Belum ada proposal yang cocok. Ubah kata kunci atau filter tahun di atas.
            </x-empty>
        </div>
    @endif

    <div class="mt-4">
        {{ $proposals->links() }}
    </div>
</x-admin-layout>
