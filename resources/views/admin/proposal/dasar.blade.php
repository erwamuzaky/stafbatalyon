<x-admin-layout title="Gunakan sebagai Dasar">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Gunakan Proposal sebagai Dasar</h2>
            <p class="mt-1 text-sm leading-relaxed text-gray-600">
                Proposal baru akan dibuat sebagai salinan. Berkas aslinya disalin, jadi proposal lama
                tetap utuh meskipun proposal baru diubah atau dihapus.
            </p>

            {{-- Proposal sumber --}}
            <div class="mt-6 rounded-xl border border-laut-100 border-l-4 border-l-emas-500 bg-laut-50 p-4 text-sm">
                <p class="text-xs font-medium tracking-wide text-laut-700">Proposal dasar</p>
                <p class="mt-1 font-medium text-arang">{{ $proposal->judul }}</p>
                <p class="mt-0.5 text-gray-600">
                    Kegiatan: {{ $proposal->event?->nama ?? '-' }}
                    @if ($proposal->event)
                        ({{ $proposal->event->tahun }})
                    @endif
                </p>
                @if ($proposal->catatan)
                    <p class="mt-1 text-gray-600">Catatan: {{ $proposal->catatan }}</p>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.proposal.dasar.store', $proposal) }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <x-input-label for="event_id" value="Kegiatan Tujuan" />
                    <select id="event_id" name="event_id" required
                            class="input-baku mt-1 block w-full">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach ($events as $pilihan)
                            <option value="{{ $pilihan->id }}" @selected(old('event_id', $proposal->event_id) == $pilihan->id)>
                                {{ $pilihan->nama }} ({{ $pilihan->tahun }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="judul" value="Judul Proposal Baru" />
                    <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full"
                                  :value="old('judul', $proposal->judul)" required />
                    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan (opsional)" />
                    <textarea id="catatan" name="catatan" rows="3"
                              class="input-baku mt-1 block w-full">{{ old('catatan') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.event.show', $proposal->event_id) }}" class="btn btn-sekunder">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
