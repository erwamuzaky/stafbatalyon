<x-admin-layout title="Ubah Periode">
    <x-card title="Ubah Periode" class="max-w-2xl">
        <form method="POST" action="{{ route('admin.periode.update', $periode) }}" class="space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="nama" value="Nama Periode" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                              :value="old('nama', $periode->nama)" required autofocus />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="tahun_mulai" value="Tahun Mulai" />
                    <x-text-input id="tahun_mulai" name="tahun_mulai" type="number" min="2000" max="2099"
                                  class="mt-1 block w-full" :value="old('tahun_mulai', $periode->tahun_mulai)" required />
                    <x-input-error :messages="$errors->get('tahun_mulai')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tahun_selesai" value="Tahun Selesai" />
                    <x-text-input id="tahun_selesai" name="tahun_selesai" type="number" min="2000" max="2099"
                                  class="mt-1 block w-full" :value="old('tahun_selesai', $periode->tahun_selesai)" required />
                    <x-input-error :messages="$errors->get('tahun_selesai')" class="mt-2" />
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                    <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                  class="mt-1 block w-full"
                                  :value="old('tanggal_mulai', $periode->tanggal_mulai?->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tanggal_selesai" value="Tanggal Selesai (opsional)" />
                    <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date"
                                  class="mt-1 block w-full"
                                  :value="old('tanggal_selesai', $periode->tanggal_selesai?->format('Y-m-d'))" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="input-baku mt-1 block w-full" required>
                    @foreach ($statuses as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('status', $periode->status) === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    Hanya satu periode yang berstatus aktif. Kalau yang ini diaktifkan, periode aktif lain otomatis jadi selesai.
                </p>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="catatan" value="Catatan (opsional)" />
                <textarea id="catatan" name="catatan" rows="3" class="input-baku mt-1 block w-full">{{ old('catatan', $periode->catatan) }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan perubahan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.periode.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>

        <div class="mt-6 rounded-lg border border-laut-100 bg-laut-50 px-3 py-2.5 text-sm text-gray-700">
            <p>Kegiatan tertaut: <span class="font-semibold text-arang">{{ $periode->events()->count() }}</span></p>
            <p class="mt-1">Data serah terima: <span class="font-semibold text-arang">{{ $periode->serahTerima()->count() + $periode->serahTerimaSebagaiLama()->count() }}</span></p>
            @if ($periode->events()->count() > 0 || $periode->serahTerima()->count() + $periode->serahTerimaSebagaiLama()->count() > 0)
                <p class="mt-1 text-xs text-gray-500">Periode ini belum bisa dihapus karena masih terhubung dengan data lain.</p>
            @endif
        </div>
    </x-card>
</x-admin-layout>
