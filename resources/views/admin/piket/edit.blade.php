<x-admin-layout title="Ubah Piket">
    <x-card class="max-w-2xl">
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Ubah Piket Gerbang</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Perbaiki tanggal, staf, atau catatan untuk satu entri piket.
                    </p>
                </div>
                <x-konfirmasi-hapus :action="route('admin.piket.destroy', $piket)"
                                    :judul="'piket '.($piket->user?->name ?? 'staf')"
                                    pesan="Entri piket ini akan dihapus dari jadwal."
                                    teks="Hapus piket" ikon="hapus" />
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.piket.update', $piket) }}" class="space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="tanggal" value="Tanggal" />
                <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                              :value="old('tanggal', $piket->tanggal->toDateString())" required />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>

            <input type="hidden" name="sesi" value="{{ old('sesi', $piket->sesi) }}">

            <div>
                <x-input-label for="user_id" value="Staf yang Bertugas" />
                <select id="user_id" name="user_id" class="input-baku mt-1 block w-full">
                    @foreach ($stafPilihan as $staf)
                        <option value="{{ $staf->id }}" @selected((int) old('user_id', $piket->user_id) === $staf->id)>
                            {{ $staf->name }}
                            @if ($staf->stafProfile)
                                · {{ $staf->stafProfile->jabatan ?: 'Tanpa jabatan' }}
                                · {{ $staf->stafProfile->bidang?->nama ?? 'Tanpa bidang' }}
                                @if ($staf->stafProfile->kelas) · Kelas {{ $staf->stafProfile->kelas }} @endif
                            @endif
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="catatan" value="Catatan (opsional)" />
                <textarea id="catatan" name="catatan" rows="3" class="input-baku mt-1 block w-full">{{ old('catatan', $piket->catatan) }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan perubahan</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.piket.index', ['minggu' => $piket->tanggal->toDateString()]) }}"
                   class="btn btn-sekunder">Kembali</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
