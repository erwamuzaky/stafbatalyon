@props([
    'name' => '',
    'size' => 'md',
    'foto' => null,
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

    $ukuran = match ($size) {
        'sm' => 'h-8 w-8 text-xs',
        'lg' => 'h-14 w-14 text-base',
        default => 'h-10 w-10 text-sm',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full ' . $ukuran . ' ' . $warna[$index]]) }}>
    @if ($foto)
        <img src="{{ $foto }}" alt="{{ $name }}" class="h-full w-full rounded-full object-cover">
    @elseif ($inisial !== '')
        <span aria-hidden="true">{{ $inisial }}</span>
        <span class="sr-only">{{ $name }}</span>
    @else
        <x-icon name="profil" class="h-4 w-4" />
    @endif
</span>