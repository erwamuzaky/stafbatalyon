<x-admin-layout title="Tambah Piket">
    <x-card title="Tambah Piket Gerbang" class="max-w-2xl">
        <p class="text-sm text-gray-600">
            Pilih tanggal dan satu atau beberapa staf yang bertugas. Semua staf yang
            dicentang akan mendapat piket pada tanggal itu.
        </p>

        <form method="POST" action="{{ route('admin.piket.store') }}" class="mt-6 space-y-5"
              x-data="{ memproses: false }" @submit="memproses = true">
            @csrf

            <div>
                <x-input-label for="tanggal" value="Tanggal" />
                <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                              :value="old('tanggal', $tanggalDefault)" required />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>

            <input type="hidden" name="sesi" value="{{ array_key_first($sesi) }}">

            <div>
                <x-input-label for="user_ids" value="Staf Piket" />

                @if ($stafPilihan->isEmpty())
                    <x-alert type="peringatan" :dismissible="false" class="mt-2">
                        Belum ada staf yang disetujui. Setujui akun staf dulu di menu Kelola Akun.
                    </x-alert>
                @else
                    <div class="mt-2 max-h-80 space-y-1 overflow-y-auto rounded-lg border border-gray-300 bg-white p-2">
                        @foreach ($stafPilihan as $staf)
                            <label class="flex cursor-pointer items-start gap-3 rounded-md px-2 py-1.5 transition-colors hover:bg-laut-50">
                                <input type="checkbox" name="user_ids[]" value="{{ $staf->id }}"
                                       @checked(in_array($staf->id, old('user_ids', [])))
                                       class="mt-0.5 rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                                <span class="flex items-center gap-2 text-sm">
                                    <span class="min-w-0">
                                        <span class="block font-medium text-arang">{{ $staf->name }}</span>
                                        <span class="block text-xs text-gray-500">
                                            @php $profil = $staf->stafProfile; @endphp
                                            @if ($profil)
                                                {{ $profil->jabatan ?: 'Tanpa jabatan' }}
                                                · {{ $profil->bidang?->nama ?? 'Tanpa bidang' }}
                                                @if ($profil->kelas) · Kelas {{ $profil->kelas }} @endif
                                            @else
                                                Belum punya profil staf
                                            @endif
                                        </span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif

                @foreach ($errors->getMessages() as $kunci => $pesan)
                    @if (str_starts_with($kunci, 'user_ids'))
                        <x-input-error :messages="$pesan" class="mt-2" />
                    @endif
                @endforeach
            </div>

            <div>
                <x-input-label for="catatan" value="Catatan (opsional)" />
                <textarea id="catatan" name="catatan" rows="3" class="input-baku mt-1 block w-full">{{ old('catatan') }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <x-primary-button x-bind:disabled="memproses">
                    <span x-show="!memproses">Simpan piket</span>
                    <span x-show="memproses" x-cloak>Menyimpan...</span>
                </x-primary-button>
                <a href="{{ route('admin.piket.index') }}" class="btn btn-sekunder">Kembali</a>
            </div>
        </form>
    </x-card>
</x-admin-layout>
