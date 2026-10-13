<x-admin-layout title="Data Staf">
    <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-judul text-lg font-semibold text-laut-800">Daftar Staf</h2>
                <p class="mt-1 text-sm leading-relaxed text-gray-600">
                    Data staf beserta bidang, jabatan, dan status intinya.
                </p>
            </div>

            <a href="{{ route('admin.staf.create') }}" class="btn btn-utama">Tambah Staf</a>
        </div>

        {{-- Filter dan pencarian --}}
        <form method="GET" action="{{ route('admin.staf.index') }}" class="mt-4 flex flex-wrap items-end gap-3">
            <div class="w-full sm:w-auto">
                <x-input-label for="q" value="Cari" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full sm:w-64"
                              :value="$q" placeholder="Cari nama atau email" />
            </div>

            <div class="w-full sm:w-auto">
                <x-input-label for="bidang_id" value="Bidang" />
                <select id="bidang_id" name="bidang_id"
                        class="input-baku mt-1 block w-full sm:w-56">
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
                   class="rounded-md text-sm font-medium text-laut-700 underline decoration-laut-200 underline-offset-2 hover:text-laut-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500">Reset</a>
            @endif
        </form>

        <p class="mt-4 text-sm text-gray-500">Menampilkan {{ $users->total() }} staf.</p>
    </div>

    {{-- Tabel untuk layar sedang ke atas --}}
    <div class="mt-4 hidden overflow-hidden rounded-xl border border-laut-100 bg-white shadow-sm sm:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-laut-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Bidang</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Jabatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Status Inti</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-laut-800">Status Akun</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-laut-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="transition-colors hover:bg-laut-50/60">
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <span class="font-medium text-arang">{{ $user->name }}</span>
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
                                    <span class="badge bg-emas-100 text-emas-800 ring-1 ring-inset ring-emas-300">
                                        Inti
                                    </span>
                                @else
                                    <span class="badge badge-selesai">
                                        Biasa
                                    </span>
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
                                    <a href="{{ route('admin.staf.edit', $user) }}"
                                       class="btn btn-sekunder px-2.5 py-1.5 text-xs">
                                        Ubah
                                    </a>

                                    <form method="POST" action="{{ route('admin.staf.destroy', $user) }}"
                                          onsubmit="return confirm('Hapus staf {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit"
                                                class="btn btn-bahaya px-2.5 py-1.5 text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                Belum ada data staf yang cocok.
                                <span class="mt-1 block text-xs">Tambahkan staf baru lewat tombol di atas atau ubah pencarian.</span>
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
            <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-arang">{{ $user->name }}</p>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        @if ($user->stafProfile?->is_inti)
                            <span class="badge bg-emas-100 text-emas-800 ring-1 ring-inset ring-emas-300">Inti</span>
                        @else
                            <span class="badge badge-selesai">Biasa</span>
                        @endif
                    </div>
                </div>
                <dl class="mt-3 space-y-1 text-xs text-gray-600">
                    <div class="flex justify-between gap-2">
                        <dt>Bidang</dt>
                        <dd class="font-medium text-arang">{{ $user->stafProfile?->bidang?->nama ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-2">
                        <dt>Jabatan</dt>
                        <dd class="font-medium text-arang">{{ $user->stafProfile?->jabatan ?? '-' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt>Status akun</dt>
                        <dd>
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
                            <span class="badge {{ $badgeHp }}">{{ $labelHp }}</span>
                        </dd>
                    </div>
                </dl>
                <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-100 pt-3">
                    <a href="{{ route('admin.staf.edit', $user) }}" class="btn btn-sekunder px-2.5 py-1.5 text-xs">Ubah</a>
                    <form method="POST" action="{{ route('admin.staf.destroy', $user) }}"
                          onsubmit="return confirm('Hapus staf {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-bahaya px-2.5 py-1.5 text-xs">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-laut-100 bg-white p-6 text-center shadow-sm">
                <p class="text-sm text-gray-500">Belum ada data staf yang cocok.</p>
                <p class="mt-1 text-xs text-gray-400">Tambahkan staf baru lewat tombol di atas atau ubah pencarian.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-admin-layout>
