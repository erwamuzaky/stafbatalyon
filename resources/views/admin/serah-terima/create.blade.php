<x-admin-layout title="Catat Serah Terima">
    <x-card title="Catat Serah Terima" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Catat penyerahan tugas dari periode lama ke periode baru, lengkap dengan berkas berita acara kalau ada.
        </p>

        <form method="POST" action="{{ route('admin.serah-terima.store') }}" enctype="multipart/form-data"
              class="mt-6 space-y-5" x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="periode_id" value="Periode Baru" />
                    <select id="periode_id" name="periode_id" class="input-baku mt-1 block w-full" required>
                        <option value="" disabled @selected(old('periode_id') === null)>Pilih periode</option>
                        @foreach ($periodes as $periode)
                            <option value="{{ $periode->id }}" @selected(old('periode_id') == $periode->id)>
                                {{ $periode->nama }} ({{ $periode->tahun_mulai }}/{{ $periode->tahun_selesai }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('periode_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="periode_lama_id" value="Dari Periode (opsional)" />
                    <select id="periode_lama_id" name="periode_lama_id" class="input-baku mt-1 block w-full">
                        <option value="">&ndash;</option>
                        @foreach ($periodes as $periode)
                            <option value="{{ $periode->id }}" @selected(old('periode_lama_id') == $periode->id)>
                                {{ $periode->nama }} ({{ $periode->tahun_mulai }}/{{ $periode->tahun_selesai }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('periode_lama_id')" class="mt-2" />
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="tanggal" value="Tanggal" />
                    <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                                  :value="old('tanggal', now()->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tempat" value="Tempat (opsional)" />
                    <x-text-input id="tempat" name="tempat" type="text" class="mt-1 block w-full"
                                  :value="old('tempat')" placeholder="Contoh: Ruang OSIS" />
                    <x-input-error :messages="$errors->get('tempat')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="catatan" value="Catatan (opsional)" />
                <textarea id="catatan" name="catatan" rows="3" class="input-baku mt-1 block w-full"
                          placeholder="Hal-hal yang diserahkan atau dicatat">{{ old('catatan') }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="berkas" value="Berkas Berita Acara (opsional)" />
                <input id="berkas" name="berkas" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                       class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-laut-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-laut-700 hover:file:bg-laut-100">
                <p class="mt-1 text-xs text-gray-500">PDF, Word, atau gambar. Maksimal 4 MB.</p>
                <x-input-error :messages="$errors->get('berkas')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.serah-terima.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
