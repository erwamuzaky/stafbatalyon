<x-admin-layout title="Cetak Absensi">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <h2 class="text-base font-semibold text-gray-800">Cetak Absensi</h2>
        <p class="mt-1 text-sm text-gray-600">
            Buat daftar hadir staf dalam bentuk PDF ukuran A4. Absensi hanya untuk dicetak,
            tidak ada absen online.
        </p>

        <form method="POST" action="{{ route('admin.absensi.cetak') }}" class="mt-6 max-w-2xl space-y-6">
            @csrf

            <div x-data="{ pilih: '' }">
                <x-input-label for="pilih_event" value="Pilih dari daftar kegiatan (opsional)" />
                <select id="pilih_event" x-model="pilih"
                        @change="if (pilih !== '') { $refs.namaKegiatan.value = pilih }"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Ketik sendiri di kolom bawah</option>
                    @foreach ($eventPilihan as $event)
                        <option value="{{ $event->nama }}">{{ $event->nama }} ({{ $event->tahun }})</option>
                    @endforeach
                </select>

                <div class="mt-4">
                    <x-input-label for="nama_kegiatan" value="Nama Kegiatan" />
                    <x-text-input id="nama_kegiatan" x-ref="namaKegiatan" name="nama_kegiatan" type="text"
                                  class="mt-1 block w-full" :value="old('nama_kegiatan')"
                                  placeholder="Misalnya: Latihan Dasar Kepemimpinan" required />
                    <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="tanggal" value="Tanggal Kegiatan" />
                <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                              :value="old('tanggal', now()->toDateString())" required />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>

            <div x-data="{ peserta: '{{ old('filter', 'semua') }}' }">
                <x-input-label value="Peserta" />

                <div class="mt-2 space-y-2">
                    @foreach ($filterPilihan as $nilai => $label)
                        <label class="flex items-center gap-3 rounded-md px-2 py-1.5 hover:bg-gray-50">
                            <input type="radio" name="filter" value="{{ $nilai }}" x-model="peserta"
                                   @checked(old('filter', 'semua') === $nilai)
                                   class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="text-sm text-gray-800">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>

                <div x-show="peserta === 'bidang'" x-cloak class="mt-3">
                    <x-input-label for="bidang_id" value="Bidang" />
                    <select id="bidang_id" name="bidang_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Pilih bidang</option>
                        @foreach ($bidangPilihan as $bidang)
                            <option value="{{ $bidang->id }}" @selected((int) old('bidang_id') === $bidang->id)>
                                {{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('bidang_id')" class="mt-2" />
                </div>

                <x-input-error :messages="$errors->get('filter')" class="mt-2" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="penandatangan" value="Nama Penandatangan (opsional)" />
                    <x-text-input id="penandatangan" name="penandatangan" type="text" class="mt-1 block w-full"
                                  :value="old('penandatangan')" placeholder="Misalnya: Rina Marlina" />
                    <x-input-error :messages="$errors->get('penandatangan')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="jabatan" value="Jabatan Penandatangan (opsional)" />
                    <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full"
                                  :value="old('jabatan', $jabatanDefault)" />
                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                </div>
            </div>

            <div class="max-w-xs">
                <x-input-label for="baris_kosong" value="Baris Kosong Tambahan" />
                <x-text-input id="baris_kosong" name="baris_kosong" type="number" min="0"
                              max="{{ $barisKosongMaksimal }}" class="mt-1 block w-full"
                              :value="old('baris_kosong', 0)" />
                <p class="mt-1 text-xs text-gray-500">
                    Untuk tamu atau peserta di luar daftar. Isi 0 sampai {{ $barisKosongMaksimal }}.
                </p>
                <x-input-error :messages="$errors->get('baris_kosong')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button name="aksi" value="lihat">Tampilkan PDF</x-primary-button>

                <button type="submit" name="aksi" value="unduh"
                        class="inline-flex items-center rounded-md border border-gray-800 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-800 shadow-sm hover:bg-gray-50">
                    Unduh PDF
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
