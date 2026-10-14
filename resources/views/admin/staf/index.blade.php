<x-admin-layout title="Data Staf">
    <x-card>
        <x-slot name="header">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-judul text-lg font-semibold text-laut-800">Daftar Staf</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Data staf beserta bidang, kelas, jabatan, dan status intinya.
                    </p>
                </div>
                <a href="{{ route('admin.staf.create') }}" class="btn btn-utama">
                    <x-icon name="tambah" class="h-4 w-4" />
                    Tambah staf
                </a>
            </div>
        </x-slot>

        {{-- Filter dan pencarian --}}
        <form method="GET" action="{{ route('admin.staf.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama atau email" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="bidang_id" value="Bidang" />
                <select id="bidang_id" name="bidang_id" class="input-baku mt-1 block w-full sm:w-48">
                    <option value="">Semua bidang</option>
                    @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}" @selected((string) $bidangId === (string) $bidang->id)>
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="kelas" value="Kelas" />
                <x-text-input id="kelas" name="kelas" list="daftar-kelas" type="text" class="mt-1 block w-full sm:w-48"
                              :value="$kelas" placeholder="Semua kelas" />
                <datalist id="daftar-kelas">
                    @foreach ($kelasList as $pilihanKelas)
                        <option value="{{ $pilihanKelas }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button>Cari</x-primary-button>

                @if (($q ?? '') !== '' || ($bidangId ?? '') !== '' || ($kelas ?? '') !== '')
                    <a href="{{ route('admin.staf.index') }}"
                       class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">Reset</a>
                @endif
            </div>
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $users->total() }} staf.</p>
    </x-card>

    @php
        $statusBadge = fn ($status) => match ($status) {
            'pending' => 'menunggu',
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            default => 'selesai',
        };
    @endphp

    <x-tabel-responsif :headers="['Nama', 'Bidang', 'Kelas', 'Jabatan', 'Status Inti', 'Status Akun', 'Aksi']" class="mt-4">
        @forelse ($users as $user)
            <tr class="transition-colors hover:bg-laut-50/50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="$user->name" :foto="$user->stafProfile?->foto_url" size="sm" />
                        <div class="min-w-0">
                            <p class="truncate font-medium text-arang">{{ $user->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $user->stafProfile?->bidang?->nama ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $user->stafProfile?->kelas ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-700">{{ $user->stafProfile?->jabatan ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if ($user->stafProfile?->is_inti)
                        <x-badge status="menunggu" label="Inti" />
                    @else
                        <x-badge status="selesai" label="Biasa" />
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-badge :status="$statusBadge($user->status)" />
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <x-aksi :href="route('admin.staf.edit', $user)" ikon="ubah" label="Ubah data staf" />
                        <x-konfirmasi-hapus :action="route('admin.staf.destroy', $user)"
                                            :judul="'staf '.$user->name" teks="Hapus staf" ikon="hapus" />
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-4 py-10">
                    <x-empty title="Belum ada data staf" ikon="staf">
                        Tambahkan staf baru lewat tombol di atas atau ubah pencarian.
                    </x-empty>
                </td>
            </tr>
        @endforelse

        <x-slot name="mobile">
            @forelse ($users as $user)
                <div class="rounded-xl border border-laut-100 p-4">
                    <div class="flex items-start justify-between gap-2">
                    <div class="flex min-w-0 items-center gap-3">
                        <x-avatar :name="$user->name" :foto="$user->stafProfile?->foto_url" size="md" />
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-arang">{{ $user->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                        @if ($user->stafProfile?->is_inti)
                            <x-badge status="menunggu" label="Inti" />
                        @else
                            <x-badge status="selesai" label="Biasa" />
                        @endif
                    </div>
                    <dl class="mt-3 space-y-1 text-xs text-gray-600">
                        <div class="flex justify-between gap-2">
                            <dt>Bidang</dt>
                            <dd class="font-medium text-arang">{{ $user->stafProfile?->bidang?->nama ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt>Kelas</dt>
                            <dd class="font-medium text-arang">{{ $user->stafProfile?->kelas ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-2">
                            <dt>Jabatan</dt>
                            <dd class="font-medium text-arang">{{ $user->stafProfile?->jabatan ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <dt>Status akun</dt>
                            <dd><x-badge :status="$statusBadge($user->status)" /></dd>
                        </div>
                    </dl>
                    <div class="mt-3 flex items-center gap-1 border-t border-gray-100 pt-3">
                        <x-aksi :href="route('admin.staf.edit', $user)" ikon="ubah" label="Ubah data staf" />
                        <x-konfirmasi-hapus :action="route('admin.staf.destroy', $user)"
                                            :judul="'staf '.$user->name" teks="Hapus staf" ikon="hapus" />
                    </div>
                </div>
            @empty
                <x-empty title="Belum ada data staf" ikon="staf">
                    Tambahkan staf baru lewat tombol di atas atau ubah pencarian.
                </x-empty>
            @endforelse
        </x-slot>
    </x-tabel-responsif>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
