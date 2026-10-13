<x-admin-layout title="Ubah Kegiatan">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="max-w-xl">
            <h2 class="text-base font-semibold text-gray-800">Ubah Kegiatan</h2>
            <p class="mt-1 text-sm text-gray-600">
                Perbarui data kegiatan {{ $event->nama }}.
            </p>

            <form method="POST" action="{{ route('admin.event.update', $event) }}" class="mt-6 space-y-5">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="nama" value="Nama Kegiatan" />
                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                                  :value="old('nama', $event->nama)" required autofocus />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tahun" value="Tahun" />
                    <x-text-input id="tahun" name="tahun" type="number" min="2000" max="2100"
                                  class="mt-1 block w-full" :value="old('tahun', $event->tahun)" required />
                    <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tanggal_mulai" value="Tanggal Mulai" />
                    <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date"
                                  class="mt-1 block w-full"
                                  :value="old('tanggal_mulai', $event->tanggal_mulai->format('Y-m-d'))" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tanggal_selesai" value="Tanggal Selesai (opsional)" />
                    <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date"
                                  class="mt-1 block w-full"
                                  :value="old('tanggal_selesai', $event->tanggal_selesai?->format('Y-m-d'))" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($statusPilihan as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(old('status', $event->status) === $nilai)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-primary-button>Simpan</x-primary-button>

                    <a href="{{ route('admin.event.show', $event) }}"
                       class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
