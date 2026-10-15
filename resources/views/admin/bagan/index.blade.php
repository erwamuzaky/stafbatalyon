<x-admin-layout title="Atur Bagan Struktur">
    <form method="POST" action="{{ route('admin.bagan.update') }}">
        @csrf
        @method('PUT')

        <x-card>
            <x-slot name="header">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="font-judul text-lg font-semibold text-laut-800">Atur Bagan Struktur</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Pilih atasan langsung tiap staf. Dari data ini bagan bercabang akan digambar.
                        </p>
                    </div>
                    <x-primary-button>Simpan bagan</x-primary-button>
                </div>
            </x-slot>

            @if ($errors->any())
                <x-alert type="error" :auto-dismiss="null" class="mb-4">
                    <p class="font-semibold">Bagan belum tersimpan</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-4">
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            @forelse ($perBidang as $namaBidang => $daftarStaf)
                <section class="mt-6 first:mt-0">
                    <h4 class="font-judul text-sm font-semibold uppercase tracking-wide text-laut-800">
                        {{ $namaBidang }}
                    </h4>

                    <div class="mt-3 divide-y divide-laut-50 overflow-hidden rounded-xl border border-laut-100">
                        @foreach ($daftarStaf as $user)
                            @php $profil = $user->stafProfile; @endphp
                            <div class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex min-w-0 items-center gap-3">
                                    <x-avatar :name="$user->name" :foto="$profil?->foto_url" size="md" />
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-arang">{{ $user->name }}</p>
                                        <p class="truncate text-xs text-gray-500">{{ $profil?->jabatan ?? 'Anggota' }}</p>
                                    </div>
                                </div>

                                @if ($profil)
                                    <div class="w-full sm:w-72">
                                        <label for="atasan-{{ $profil->id }}" class="sr-only">
                                            Atasan langsung {{ $user->name }}
                                        </label>
                                        <select id="atasan-{{ $profil->id }}"
                                                name="atasan[{{ $profil->id }}]"
                                                class="input-baku block w-full">
                                            <option value="">— Tidak ada atasan —</option>
                                            @foreach ($profils as $pilihan)
                                                @continue($pilihan->id === $profil->id)
                                                <option value="{{ $pilihan->id }}"
                                                        @selected((int) old("atasan.{$profil->id}", $profil->atasan_id) === $pilihan->id)>
                                                    {{ $pilihan->user?->name }}{{ $pilihan->bidang ? ' · '.$pilihan->bidang->nama : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <x-empty title="Belum ada staf disetujui" ikon="staf">
                    Setujui akun staf dulu di halaman Kelola Akun.
                </x-empty>
            @endforelse

            <x-slot name="footer">
                <div class="flex justify-end">
                    <x-primary-button>Simpan bagan</x-primary-button>
                </div>
            </x-slot>
        </x-card>
    </form>
</x-admin-layout>
