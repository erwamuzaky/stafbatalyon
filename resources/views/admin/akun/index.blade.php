<x-admin-layout title="Kelola Akun">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <h2 class="font-judul text-lg font-semibold text-laut-800">Daftar Akun</h2>
        <p class="mt-1 text-sm leading-relaxed text-gray-600">
            Setujui pendaftar baru supaya bisa masuk, tolak yang tidak sesuai, atau nonaktifkan
            akun yang sudah ada. Kata sandi hanya bisa diganti lewat tombol reset.
        </p>

        {{-- Filter status dan pencarian --}}
        <form method="GET" action="{{ route('admin.akun.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
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
    </div>

    {{-- Tampilan tabel untuk layar sedang ke atas --}}
    <div class="mt-4 hidden overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-laut-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="transition-colors hover:bg-laut-50/60">
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <span class="font-medium text-arang">{{ $user->name }}</span>
                                @if ($user->is(auth()->user()))
                                    <span class="ml-1 text-xs text-gray-500">(Akun Anda)</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-500">{{ $user->email }}</td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                                @if ($user->stafProfile?->jabatan)
                                    <span class="block text-xs text-gray-500">{{ $user->stafProfile->jabatan }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                @php
                                    $badge = match ($user->status) {
                                        'pending' => 'badge-menunggu',
                                        'approved' => 'badge-disetujui',
                                        'rejected' => 'badge-ditolak',
                                        default => 'badge-selesai',
                                    };
                                    $label = match ($user->status) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => $user->status,
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @unless ($user->status === 'approved')
                                        <form method="POST" action="{{ route('admin.akun.approve', $user) }}">
                                            @csrf
                                            @method('patch')
                                            <button type="submit"
                                                    class="btn btn-utama px-2.5 py-1.5 text-xs">
                                                Setujui
                                            </button>
                                        </form>
                                    @endunless

                                    @if (! $user->is(auth()->user()))
                                        @unless ($user->status === 'rejected')
                                            <form method="POST" action="{{ route('admin.akun.reject', $user) }}">
                                                @csrf
                                                @method('patch')
                                                <button type="submit"
                                                        class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endunless

                                        @if ($user->status === 'approved')
                                            <form method="POST" action="{{ route('admin.akun.deactivate', $user) }}">
                                                @csrf
                                                @method('patch')
                                                <button type="submit"
                                                        class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <form method="POST" action="{{ route('admin.akun.reset-password', $user) }}"
                                          onsubmit="return confirm('Buat kata sandi baru untuk {{ $user->name }}? Kata sandi lama tidak akan berlaku lagi.')">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                            Reset kata sandi
                                        </button>
                                    </form>

                                    @if (! $user->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.akun.destroy', $user) }}"
                                              onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                    class="btn btn-bahaya px-2.5 py-1.5 text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                Belum ada akun yang cocok dengan filter atau pencarian.
                                <span class="mt-1 block text-xs">Ubah kata kunci atau atur ulang filter di atas.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Daftar bertumpuk untuk layar HP --}}
    <div class="mt-4 space-y-3 sm:hidden">
        @forelse ($users as $user)
            @php
                $badgeHp = match ($user->status) {
                    'pending' => 'badge-menunggu',
                    'approved' => 'badge-disetujui',
                    'rejected' => 'badge-ditolak',
                    default => 'badge-selesai',
                };
                $labelHp = match ($user->status) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => $user->status,
                };
            @endphp
            <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-arang">{{ $user->name }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $user->email }}</p>
                        @if ($user->is(auth()->user()))
                            <p class="mt-0.5 text-xs text-gray-500">(Akun Anda)</p>
                        @endif
                    </div>
                    <span class="badge {{ $badgeHp }}">{{ $labelHp }}</span>
                </div>
                <p class="mt-2 text-xs text-gray-600">
                    Bidang: <span class="font-medium text-arang">{{ $user->stafProfile?->bidang?->nama ?? '-' }}</span>
                    @if ($user->stafProfile?->jabatan)
                        <span class="text-gray-500">({{ $user->stafProfile->jabatan }})</span>
                    @endif
                </p>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    @unless ($user->status === 'approved')
                        <form method="POST" action="{{ route('admin.akun.approve', $user) }}">
                            @csrf
                            @method('patch')
                            <button type="submit" class="btn btn-utama px-2.5 py-1.5 text-xs">Setujui</button>
                        </form>
                    @endunless
                    @if (! $user->is(auth()->user()))
                        @unless ($user->status === 'rejected')
                            <form method="POST" action="{{ route('admin.akun.reject', $user) }}">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Tolak</button>
                            </form>
                        @endunless
                        @if ($user->status === 'approved')
                            <form method="POST" action="{{ route('admin.akun.deactivate', $user) }}">
                                @csrf
                                @method('patch')
                                <button type="submit" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Nonaktifkan</button>
                            </form>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('admin.akun.reset-password', $user) }}"
                          onsubmit="return confirm('Buat kata sandi baru untuk {{ $user->name }}? Kata sandi lama tidak akan berlaku lagi.')">
                        @csrf
                        <button type="submit" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Reset kata sandi</button>
                    </form>
                    @if (! $user->is(auth()->user()))
                        <form method="POST" action="{{ route('admin.akun.destroy', $user) }}"
                              onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-bahaya px-2.5 py-1.5 text-xs">Hapus</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-laut-100 bg-white p-6 text-center shadow-sm">
                <p class="text-sm text-gray-500">Belum ada akun yang cocok dengan filter atau pencarian.</p>
                <p class="mt-1 text-xs text-gray-400">Ubah kata kunci atau atur ulang filter di atas.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
