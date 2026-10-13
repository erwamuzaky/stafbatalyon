<x-admin-layout title="Kelola Akun">
    <x-card>
        <x-slot name="header">
            <h3 class="font-judul text-lg font-semibold text-laut-800">Daftar Akun</h3>
            <p class="mt-1 text-sm text-gray-600">
                Setujui pendaftar baru supaya bisa masuk, tolak yang tidak sesuai, atau nonaktifkan
                akun yang sudah ada. Kata sandi hanya bisa diganti lewat tombol reset.
            </p>
        </x-slot>

        {{-- Filter status dan pencarian --}}
        <form method="GET" action="{{ route('admin.akun.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" onchange="this.form.submit()"
                        class="input-baku mt-1 block w-full sm:w-56">
                    <option value="" @selected(! in_array($status, ['pending', 'approved', 'rejected'], true))>
                        Semua status
                    </option>
                    <option value="pending" @selected($status === 'pending')>
                        Menunggu persetujuan
                    </option>
                    <option value="approved" @selected($status === 'approved')>
                        Disetujui
                    </option>
                    <option value="rejected" @selected($status === 'rejected')>
                        Ditolak
                    </option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama atau email" />
            </div>

            <x-primary-button>Cari</x-primary-button>

            @if (($q ?? '') !== '' || in_array($status, ['pending', 'approved', 'rejected'], true))
                <a href="{{ route('admin.akun.index') }}"
                   class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $users->total() }} akun.</p>
    </x-card>

    @php
        $statusBadge = fn ($status) => match ($status) {
            'pending' => 'menunggu',
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            default => 'selesai',
        };
    @endphp

    <x-tabel-responsif :headers="['Nama', 'Email', 'Bidang', 'Status', 'Aksi']" class="mt-4">
        @forelse ($users as $user)
            <tr class="transition-colors hover:bg-laut-50/50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="$user->name" size="sm" />
                        <div class="min-w-0">
                            <p class="truncate font-medium text-arang">{{ $user->name }}</p>
                            @if ($user->is(auth()->user()))
                                <p class="text-xs text-gray-500">Akun Anda</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-4 py-3 text-gray-700">
                    {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                    @if ($user->stafProfile?->jabatan)
                        <span class="block text-xs text-gray-500">{{ $user->stafProfile->jabatan }}</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-badge :status="$statusBadge($user->status)" />
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        @unless ($user->status === 'approved')
                            <form method="POST" action="{{ route('admin.akun.approve', $user) }}">
                                @csrf
                                @method('patch')
                                <x-aksi ikon="setuju" label="Setujui akun" />
                            </form>
                        @endunless

                        @if (! $user->is(auth()->user()))
                            @unless ($user->status === 'rejected')
                                <form method="POST" action="{{ route('admin.akun.reject', $user) }}">
                                    @csrf
                                    @method('patch')
                                    <x-aksi ikon="tolak" label="Tolak akun" />
                                </form>
                            @endunless

                            @if ($user->status === 'approved')
                                <form method="POST" action="{{ route('admin.akun.deactivate', $user) }}">
                                    @csrf
                                    @method('patch')
                                    <x-aksi ikon="silang" label="Nonaktifkan akun" />
                                </form>
                            @endif
                        @endif

                        <form method="POST" action="{{ route('admin.akun.reset-password', $user) }}"
                              onsubmit="return confirm('Buat kata sandi baru untuk {{ $user->name }}? Kata sandi lama tidak akan berlaku lagi.')">
                            @csrf
                            <x-aksi ikon="kunci" label="Reset kata sandi" />
                        </form>

                        @if (! $user->is(auth()->user()))
                            <x-konfirmasi-hapus :action="route('admin.akun.destroy', $user)"
                                                :judul="'akun '.$user->name" teks="Hapus akun" ikon="hapus" />
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-10">
                    <x-empty title="Belum ada akun" ikon="akun">
                        Belum ada akun yang cocok dengan filter atau pencarian. Ubah kata kunci atau atur ulang filter di atas.
                    </x-empty>
                </td>
            </tr>
        @endforelse

        <x-slot name="mobile">
            @forelse ($users as $user)
                <div class="rounded-xl border border-laut-100 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-avatar :name="$user->name" size="md" />
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-arang">{{ $user->name }}</p>
                                <p class="truncate text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <x-badge :status="$statusBadge($user->status)" />
                    </div>
                    <p class="mt-2 text-xs text-gray-600">
                        Bidang: <span class="font-medium text-arang">{{ $user->stafProfile?->bidang?->nama ?? '-' }}</span>
                        @if ($user->stafProfile?->jabatan)
                            <span class="text-gray-500">({{ $user->stafProfile->jabatan }})</span>
                        @endif
                    </p>
                    <div class="mt-3 flex flex-wrap items-center gap-1 border-t border-gray-100 pt-3">
                        @unless ($user->status === 'approved')
                            <form method="POST" action="{{ route('admin.akun.approve', $user) }}">
                                @csrf
                                @method('patch')
                                <x-aksi ikon="setuju" label="Setujui akun" />
                            </form>
                        @endunless
                        @if (! $user->is(auth()->user()))
                            @unless ($user->status === 'rejected')
                                <form method="POST" action="{{ route('admin.akun.reject', $user) }}">
                                    @csrf
                                    @method('patch')
                                    <x-aksi ikon="tolak" label="Tolak akun" />
                                </form>
                            @endunless
                            @if ($user->status === 'approved')
                                <form method="POST" action="{{ route('admin.akun.deactivate', $user) }}">
                                    @csrf
                                    @method('patch')
                                    <x-aksi ikon="silang" label="Nonaktifkan akun" />
                                </form>
                            @endif
                        @endif
                        <form method="POST" action="{{ route('admin.akun.reset-password', $user) }}"
                              onsubmit="return confirm('Buat kata sandi baru untuk {{ $user->name }}? Kata sandi lama tidak akan berlaku lagi.')">
                            @csrf
                            <x-aksi ikon="kunci" label="Reset kata sandi" />
                        </form>
                        @if (! $user->is(auth()->user()))
                            <x-konfirmasi-hapus :action="route('admin.akun.destroy', $user)"
                                                :judul="'akun '.$user->name" teks="Hapus akun" ikon="hapus" />
                        @endif
                    </div>
                </div>
            @empty
                <x-empty title="Belum ada akun" ikon="akun">
                    Belum ada akun yang cocok dengan filter atau pencarian.
                </x-empty>
            @endforelse
        </x-slot>
    </x-tabel-responsif>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
