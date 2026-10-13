<x-admin-layout title="Data Staf">
    <div class="rounded-lg bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-gray-800">Daftar Staf</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Data staf beserta bidang, jabatan, dan status intinya.
                </p>
            </div>

            <a href="{{ route('admin.staf.create') }}"
               class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                Tambah Staf
            </a>
        </div>

        {{-- Filter dan pencarian --}}
        <form method="GET" action="{{ route('admin.staf.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div>
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama atau email" />
            </div>

            <div>
                <x-input-label for="bidang_id" value="Bidang" />
                <select id="bidang_id" name="bidang_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-56">
                    <option value="">Semua bidang</option>
                    @foreach ($bidangs as $bidang)
                        <option value="{{ $bidang->id }}" @selected((string) $bidangId === (string) $bidang->id)>
                            {{ $bidang->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-primary-button>Cari</x-primary-button>

            @if (($q ?? '') !== '' || ($bidangId ?? '') !== '')
                <a href="{{ route('admin.staf.index') }}"
                   class="text-sm font-medium text-gray-600 underline hover:text-gray-900">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $users->total() }} staf.</p>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Jabatan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status Inti</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status Akun</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                <span class="block text-xs text-gray-500">{{ $user->email }}</span>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $user->stafProfile?->bidang?->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $user->stafProfile?->jabatan ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-sm">
                                @if ($user->stafProfile?->is_inti)
                                    <span class="inline-flex rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">
                                        Inti
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                                        Biasa
                                    </span>
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
                                    <a href="{{ route('admin.staf.edit', $user) }}"
                                       class="inline-flex items-center rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Ubah
                                    </a>

                                    <form method="POST" action="{{ route('admin.staf.destroy', $user) }}"
                                          onsubmit="return confirm('Hapus staf {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md border border-red-300 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada data staf yang cocok.
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
