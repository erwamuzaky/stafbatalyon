<x-admin-layout title="Ubah Surat">
    <x-card title="Ubah Surat" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Perubahan nomor surat harus unik. Hindari mengubah nomor setelah surat tercetak.
        </p>

        <form method="POST" action="{{ route('admin.surat.update', $surat) }}" class="mt-6 space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf
            @method('PATCH')

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="jenis" value="Jenis Surat" />
                    <select id="jenis" name="jenis" class="input-baku mt-1 block w-full" required>
                        @foreach ($jenisPilihan as $k => $v)
                            <option value="{{ $k }}" @selected(old('jenis', $surat->jenis) === $k)>{{ $v['label'] }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('jenis')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tanggal" value="Tanggal Surat" />
                    <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                                  :value="old('tanggal', $surat->tanggal?->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="nomor" value="Nomor Surat" />
                <x-text-input id="nomor" name="nomor" type="text" class="mt-1 block w-full"
                              :value="old('nomor', $surat->nomor)" required />
                <x-input-error :messages="$errors->get('nomor')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="perihal" value="Perihal" />
                <textarea id="perihal" name="perihal" rows="2" class="input-baku mt-1 block w-full"
                          required>{{ old('perihal', $surat->perihal) }}</textarea>
                <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="tujuan" value="Kepada (tujuan)" />
                    <x-text-input id="tujuan" name="tujuan" type="text" class="mt-1 block w-full"
                                  :value="old('tujuan', $surat->tujuan)" />
                    <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tempat" value="Tempat" />
                    <x-text-input id="tempat" name="tempat" type="text" class="mt-1 block w-full"
                                  :value="old('tempat', $surat->tempat)" required />
                    <x-input-error :messages="$errors->get('tempat')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="isi" value="Isi Surat (opsional)" />
                <textarea id="isi" name="isi" rows="5" class="input-baku mt-1 block w-full">{{ old('isi', $surat->isi) }}</textarea>
                <x-input-error :messages="$errors->get('isi')" class="mt-2" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="penandatangan" value="Penanda Tangan" />
                    <x-text-input id="penandatangan" name="penandatangan" type="text" class="mt-1 block w-full"
                                  :value="old('penandatangan', $surat->penandatangan)" required />
                    <x-input-error :messages="$errors->get('penandatangan')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="jabatan" value="Jabatan Penanda Tangan" />
                    <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                                  :value="old('jabatan', $surat->jabatan)" required />
                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="periode_id" value="Periode (opsional)" />
                    <select id="periode_id" name="periode_id" class="input-baku mt-1 block w-full">
                        <option value="">&ndash;</option>
                        @foreach ($periodes as $periode)
                            <option value="{{ $periode->id }}" @selected(old('periode_id', $surat->periode_id) == $periode->id)>
                                {{ $periode->nama }} ({{ $periode->tahun_mulai }}/{{ $periode->tahun_selesai }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('periode_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="event_id" value="Kegiatan/Event (opsional)" />
                    <select id="event_id" name="event_id" class="input-baku mt-1 block w-full">
                        <option value="">&ndash;</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}" @selected(old('event_id', $surat->event_id) == $event->id)>
                                {{ $event->nama }} ({{ $event->tahun }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan perubahan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.surat.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
