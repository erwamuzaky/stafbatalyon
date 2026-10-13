<x-admin-layout title="Kelola Akun">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <h2 class="text-base font-semibold text-gray-800">Daftar Akun</h2>
        <p class="mt-1 text-sm text-gray-600">
            Setujui pendaftar baru supaya bisa masuk, tolak yang tidak sesuai, atau nonaktifkan
            akun yang sudah ada. Kata sandi hanya bisa diganti lewat tombol reset.
        </p>

        {{-- Filter status dan pencarian --}}
        <form method="GET" action="{{ route('admin.akun.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" onchange="this.form.submit()"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-56">
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

            <div>
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama atau email" />
            </div>

            <x-primary-button>Cari</x-primary-button>

            @if (($q ?? '') !== '' || in_array($status, ['pending', 'approved', 'rejected'], true))
                <a href="{{ route('admin.akun.index') }}"
                   class="text-sm font-medium text-gray-600 underline hover:text-gray-900">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $users->total() }} akun.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
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
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                    $label = match ($user->status) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => $user->status,
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex flex-wrap items-center gap-2">
                                    @unless ($user->status === 'approved')
                                        <form method="POST" action="{{ route('admin.akun.approve', $user) }}">
                                            @csrf
                                            @method('patch')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-green-300 px-2.5 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50">
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
                                                        class="inline-flex items-center rounded-md border border-amber-300 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endunless

                                        @if ($user->status === 'approved')
                                            <form method="POST" action="{{ route('admin.akun.deactivate', $user) }}">
                                                @csrf
                                                @method('patch')
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <form method="POST" action="{{ route('admin.akun.reset-password', $user) }}"
                                          onsubmit="return confirm('Buat kata sandi baru untuk {{ $user->name }}? Kata sandi lama tidak akan berlaku lagi.')">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md border border-indigo-300 px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50">
                                            Reset kata sandi
                                        </button>
                                    </form>

                                    @if (! $user->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.akun.destroy', $user) }}"
                                              onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-red-300 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada akun yang cocok dengan filter atau pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
