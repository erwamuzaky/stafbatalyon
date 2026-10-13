<x-admin-layout title="Ubah Piket">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="font-judul text-lg font-semibold text-laut-800">Ubah Piket Gerbang</h2>
                    <p class="mt-1 text-sm leading-relaxed text-gray-600">
                        Perbaiki tanggal, staf, atau catatan untuk satu entri piket.
                    </p>
                </div>

                <x-konfirmasi-hapus :action="route('admin.piket.destroy', $piket)"
                                    :judul="'piket '.($piket->user?->name ?? 'staf')"
                                    pesan="Entri piket ini akan dihapus dari jadwal."
                                    teks="Hapus Piket" />
            </div>

            <form method="POST" action="{{ route('admin.piket.update', $piket) }}" class="mt-6 space-y-5">
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
                    <select id="user_id" name="user_id"
                            class="input-baku mt-1 block w-full">
                        @foreach ($stafPilihan as $staf)
                            <option value="{{ $staf->id }}" @selected((int) old('user_id', $piket->user_id) === $staf->id)>
                                {{ $staf->name }} ({{ $staf->email }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="catatan" value="Catatan (opsional)" />
                    <textarea id="catatan" name="catatan" rows="3"
                              class="input-baku mt-1 block w-full">{{ old('catatan', $piket->catatan) }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.piket.index', ['minggu' => $piket->tanggal->toDateString()]) }}"
                       class="btn btn-sekunder">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
