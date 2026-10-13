<x-admin-layout title="Tambah Piket">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <h2 class="text-base font-semibold text-gray-800">Tambah Piket Gerbang</h2>
        <p class="mt-1 text-sm text-gray-600">
            Pilih tanggal, sesi, dan satu atau beberapa staf yang bertugas. Semua staf yang
            dicentang akan mendapat piket pada tanggal dan sesi yang sama.
        </p>

        <form method="POST" action="{{ route('admin.piket.store') }}" class="mt-6 max-w-2xl space-y-5">
            @csrf

            <div>
                <x-input-label for="tanggal" value="Tanggal" />
                <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                              :value="old('tanggal', $tanggalDefault)" required />
                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="sesi" value="Sesi" />
                <select id="sesi" name="sesi"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($sesi as $nilai => $label)
                        <option value="{{ $nilai }}" @selected(old('sesi') === $nilai)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('sesi')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="user_ids" value="Staf yang Bertugas" />

                @if ($stafPilihan->isEmpty())
                    <p class="mt-1 rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-800">
                        Belum ada staf yang disetujui. Setujui akun staf dulu di menu Kelola Akun.
                    </p>
                @else
                    <div class="mt-2 max-h-72 space-y-2 overflow-y-auto rounded-md border border-gray-300 p-3">
                        @foreach ($stafPilihan as $staf)
                            <label class="flex items-start gap-3 rounded-md px-2 py-1.5 hover:bg-gray-50">
                                <input type="checkbox" name="user_ids[]" value="{{ $staf->id }}"
                                       @checked(in_array($staf->id, old('user_ids', [])))
                                       class="mt-0.5 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="text-sm">
                                    <span class="block font-medium text-gray-800">{{ $staf->name }}</span>
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
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-primary-button>Simpan Piket</x-primary-button>

                <a href="{{ route('admin.piket.index') }}"
                   class="text-sm font-medium text-gray-600 underline hover:text-gray-900">Kembali</a>
            </div>
        </form>
    </div>
</x-admin-layout>
