<x-admin-layout title="Piket Otomatis">
    <x-card title="Pengisian Otomatis Piket Gerbang" class="max-w-4xl">
        <p class="text-sm text-gray-600">
            Buat jadwal piket otomatis untuk satu minggu (Senin&ndash;Sabtu) atau satu
            bulan. Sistem membagi staf secara berurutan supaya tiap orang dapat giliran
            seimbang. Kamu bisa pratinjau dulu sebelum menyimpan, dan entri yang sudah ada
            dilewati otomatis (tidak duplikat).
        </p>

        <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
            Staf eligible: <span class="font-medium text-arang">{{ $stafEligible->count() }}</span>
        </div>

        <form method="POST" action="{{ route('admin.piket.otomatis.preview') }}"
              class="mt-5 space-y-5" x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <x-input-label value="Cakupan" />
                    <select name="scope" class="input-baku mt-1 block w-full">
                        <option value="minggu" @selected(old('scope', $scope ?? 'minggu') === 'minggu')>Satu minggu (6 hari)</option>
                        <option value="bulan" @selected(old('scope', $scope ?? '') === 'bulan')>Satu bulan (Senin&ndash;Sabtu)</option>
                    </select>
                    <x-input-error :messages="$errors->get('scope')" class="mt-2" />
                </div>
                <div>
                    <x-input-label value="Minggu (kalau satu minggu)" />
                    <x-text-input name="minggu" type="date" class="input-baku mt-1 block w-full"
                                  :value="old('minggu', $mingguDefault)" />
                    <p class="mt-1 text-xs text-gray-500">Isi tanggal apa saja dalam minggu yang diinginkan.</p>
                    <x-input-error :messages="$errors->get('minggu')" class="mt-2" />
                </div>
                <div>
                    <x-input-label value="Bulan (kalau satu bulan)" />
                    <x-text-input name="bulan" type="month" class="input-baku mt-1 block w-full"
                                  :value="old('bulan', $bulanDefault)" />
                    <x-input-error :messages="$errors->get('bulan')" class="mt-2" />
                </div>
            </div>

            <div class="max-w-xs">
                <x-input-label for="per_day" value="Staf per hari" />
                <x-text-input id="per_day" name="per_day" type="number" min="1" max="5" class="mt-1 block w-full"
                              :value="old('per_day', $perDay ?? 1)" required />
                <p class="mt-1 text-xs text-gray-500">Banyak staf piket yang dibutuhkan tiap hari.</p>
                <x-input-error :messages="$errors->get('per_day')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <x-icon name="cetak" class="h-4 w-4" />
                    <span x-show="!memproses">Buat Pratinjau</span>
                    <span x-show="memproses" x-cloak>Memproses...</span>
                </x-primary-button>
                <a href="{{ route('admin.piket.index') }}" class="btn btn-sekunder">Kembali</a>
            </div>
        </form>

        @isset($rencana)
            <div class="mt-6 border-t border-gray-100 pt-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="font-judul text-sm font-semibold text-laut-800">
                        Pratinjau Rancangan ({{ count($rencana) }} hari)
                    </h3>
                    <span class="text-xs text-gray-500">
                        Total staf unik hari ini ditetapkan per baris.
                    </span>
                </div>

                <x-tabel-responsif :headers="['Hari', 'Tanggal', 'Staf Piket', 'Jumlah']" class="mt-3">
                    @foreach ($rencana as $baris)
                        <tr>
                            <td class="px-4 py-3 text-gray-700">{{ $baris['hari'] }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $baris['tanggalLabel'] }}</td>
                            <td class="px-4 py-3">
                                @if (count($baris['user_names']) === 0)
                                    <span class="text-xs text-gray-400">Tidak ada staf (dilewati)</span>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($baris['user_names'] as $nama)
                                            <span class="rounded bg-laut-50 px-2 py-0.5 text-xs text-laut-700">{{ $nama }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ count($baris['user_names']) }}</td>
                        </tr>
                    @endforeach
                </x-tabel-responsif>

                <form method="POST" action="{{ route('admin.piket.otomatis.simpan') }}"
                      class="mt-5 space-y-4" x-data="{ memproses: false }" @submit="memproses = true">
                    @csrf
                    <input type="hidden" name="rencana" value="{{ $rencanaEncoded }}">

                    <div>
                        <x-input-label for="catatan" value="Catatan untuk yang tersimpan (opsional)" />
                        <x-text-input id="catatan" name="catatan" type="text" class="mt-1 block w-full"
                                      :value="old('catatan')" placeholder="Misalnya: Piket otomatis bulan Maret" />
                    </div>

                    <div class="rounded-lg bg-marun-50 p-3 text-sm text-marun-800">
                        Perhatian: entri yang sudah ada pada tanggal yang sama akan dilewati, tidak duplikat.
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <x-primary-button x-bind:disabled="memproses">
                            <span x-show="!memproses">Simpan Semua ke Jadwal</span>
                            <span x-show="memproses" x-cloak>Menyimpan...</span>
                        </x-primary-button>
                        <a href="{{ route('admin.piket.index') }}" class="btn btn-sekunder">Batal</a>
                    </div>
                </form>
            </div>
        @endisset
    </x-card>
</x-admin-layout>
