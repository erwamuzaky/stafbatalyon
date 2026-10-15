@props([
    'name' => '',
    'foto' => null,
    'jabatan' => null,
    'bidang' => null,
    'kelas' => null,
])

@php
    $kata = preg_split('/\s+/', trim($name)) ?: [];
    $pertama = $kata[0] ?? '';
    $terakhir = count($kata) > 1 ? end($kata) : '';
    $inisial = mb_strtoupper(mb_substr($pertama, 0, 1) . ($terakhir !== $pertama ? mb_substr($terakhir, 0, 1) : ''));

    $warna = [
        'bg-laut-100 text-laut-700',
        'bg-emas-100 text-emas-700',
        'bg-info-100 text-info-700',
        'bg-sukses-100 text-sukses-700',
        'bg-peringatan-100 text-peringatan-700',
        'bg-marun-100 text-marun-700',
    ];
    $index = abs(crc32(mb_strtolower($name))) % count($warna);
@endphp

<div {{ $attributes->merge(['class' => 'flex h-full flex-col rounded-3xl border border-laut-100 bg-white p-3 sm:p-4']) }}>
    <div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-laut-50 shadow-soft-md">
        @if ($foto)
            <img src="{{ $foto }}" alt="Foto {{ $name }}" class="h-full w-full object-cover">
        @elseif ($inisial !== '')
            <div class="flex h-full w-full items-center justify-center font-judul text-4xl font-bold {{ $warna[$index] }}">
                <span aria-hidden="true">{{ $inisial }}</span>
                <span class="sr-only">{{ $name }}</span>
            </div>
        @else
            <div class="flex h-full w-full items-center justify-center text-laut-400">
                <x-icon name="profil" class="h-10 w-10" />
            </div>
        @endif

        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>

        <div class="absolute inset-x-0 bottom-0 flex flex-col items-center justify-end px-3 py-2 text-center sm:px-4 sm:py-3">
            @if ($jabatan)
                <p class="font-judul text-sm font-bold leading-tight text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)] sm:text-base">{{ $jabatan }}</p>
            @endif
            <p class="mt-0.5 text-xs font-semibold text-white drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)] sm:text-sm">{{ $name }}</p>
        </div>
    </div>

    <div class="mt-3 flex-1 text-center">
        @if ($bidang)
            <span class="inline-flex items-center rounded-full bg-info-50 px-3 py-1 text-xs font-medium text-info-700">{{ $bidang }}</span>
        @endif
        @if ($kelas)
            <p class="mt-2 text-sm text-gray-500">Kelas {{ $kelas }}</p>
        @endif
    </div>
</div>
