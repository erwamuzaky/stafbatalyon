<x-admin-layout title="Tambah Piket">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-2xl">
            <h2 class="font-judul text-lg font-semibold text-laut-800">Tambah Piket Gerbang</h2>
            <p class="mt-1 text-sm leading-relaxed text-gray-600">
                Pilih tanggal dan satu atau beberapa staf yang bertugas. Semua staf yang
                dicentang akan mendapat piket pada tanggal itu.
            </p>

            <form method="POST" action="{{ route('admin.piket.store') }}" class="mt-6 space-y-5">
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
                        <p class="mt-2 rounded-lg border border-emas-300 bg-emas-50 px-3 py-2 text-sm text-emas-800">
                            Belum ada staf yang disetujui. Setujui akun staf dulu di menu Kelola Akun.
                        </p>
                    @else
                        <div class="mt-2 max-h-72 space-y-1 overflow-y-auto rounded-lg border border-gray-300 bg-white p-2">
                            @foreach ($stafPilihan as $staf)
                                <label class="flex cursor-pointer items-start gap-3 rounded-md px-2 py-1.5 transition-colors hover:bg-laut-50">
                                    <input type="checkbox" name="user_ids[]" value="{{ $staf->id }}"
                                           @checked(in_array($staf->id, old('user_ids', [])))
                                           class="mt-0.5 rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
                                    <span class="text-sm">
                                        <span class="block font-medium text-arang">{{ $staf->name }}</span>
                                        <span class="block text-xs text-gray-500">{{ $staf->email }}</span>
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
                    <textarea id="catatan" name="catatan" rows="3"
                              class="input-baku mt-1 block w-full">{{ old('catatan') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.piket.index') }}" class="btn btn-sekunder">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
