<x-admin-layout title="Tambah Periode">
    <x-card title="Tambah Periode Baru" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Periode menjadi acuan tahun kepengurusan. Nama bebas, contoh: "Kepengurusan 2026/2027".
        </p>

        <form method="POST" action="{{ route('admin.periode.store') }}" class="mt-6 space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div>
                <x-input-label for="nama" value="Nama Periode" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                              :value="old('nama')" required autofocus placeholder="Contoh: Kepengurusan 2026/2027" />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="tahun_mulai" value="Tahun Mulai" />
                    <x-text-input id="tahun_mulai" name="tahun_mulai" type="number" min="2000" max="2099"
                                  class="mt-1 block w-full" :value="old('tahun_mulai')" required placeholder="2026" />
                    <x-input-error :messages="$errors->get('tahun_mulai')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tahun_selesai" value="Tahun Selesai" />
                    <x-text-input id="tahun_selesai" name="tahun_selesai" type="number" min="2000" max="2099"
                                  class="mt-1 block w-full" :value="old('tahun_selesai')" required placeholder="2027" />
                    <x-input-error :messages="$errors->get('tahun_selesai')" class="mt-2" />
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                    <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                  class="mt-1 block w-full" :value="old('tanggal_mulai')" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tanggal_selesai" value="Tanggal Selesai (opsional)" />
                    <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date"
                                  class="mt-1 block w-full" :value="old('tanggal_selesai')" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="input-baku mt-1 block w-full" required>
                    @foreach ($statuses as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('status', 'aktif') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">
                    Hanya satu periode yang berstatus aktif. Kalau yang ini diaktifkan, periode aktif lain otomatis jadi selesai.
                </p>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="catatan" value="Catatan (opsional)" />
                <textarea id="catatan" name="catatan" rows="3" class="input-baku mt-1 block w-full"
                          placeholder="Catatan singkat tentang periode ini">{{ old('catatan') }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan periode</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.periode.index') }}" class="btn btn-sekunder">Batal</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
