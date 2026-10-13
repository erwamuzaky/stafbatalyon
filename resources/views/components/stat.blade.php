@props([
    'label' => '',
    'value' => '0',
    'variant' => 'info',
    'ikon' => null,
    'href' => null,
    'deskripsi' => null,
    'menonjol' => false,
])

@php
    $latar = match ($variant) {
        'peringatan' => 'bg-peringatan-50 text-peringatan-700',
        'sukses' => 'bg-sukses-50 text-sukses-700',
        'bahaya' => 'bg-bahaya-50 text-bahaya-700',
        'laut' => 'bg-laut-50 text-laut-700',
        default => 'bg-info-50 text-info-700',
    };
    $chip = match ($variant) {
        'peringatan' => 'bg-peringatan-100 text-peringatan-700',
        'sukses' => 'bg-sukses-100 text-sukses-700',
        'bahaya' => 'bg-bahaya-100 text-bahaya-700',
        'laut' => 'bg-laut-100 text-laut-700',
        default => 'bg-info-100 text-info-700',
    };
    $kelas = $latar . ' border ' . ($menonjol ? 'ring-2 ring-peringatan-300 shadow-soft-md ' : 'shadow-soft-sm ') . 'p-5';
@endphp

@if ($href)
    <a href="{{ $href }}"
       class="{{ $kelas }} flex items-start justify-between gap-4 rounded-2xl transition-colors hover:opacity-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-laut-500 focus-visible:ring-offset-2 focus-visible:ring-offset-tulang">
        <div class="min-w-0">
            <p class="text-sm font-medium text-arang/80">{{ $label }}</p>
            <p class="mt-1 font-judul text-4xl font-semibold">{{ $value }}</p>
            @if ($deskripsi)
                <p class="mt-1 text-xs leading-relaxed text-arang/60">{{ $deskripsi }}</p>
            @endif
        </div>
        @if ($ikon)
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $chip }}">
                <x-icon :name="$ikon" />
            </span>
        @endif
    </a>
@else
    <div class="{{ $kelas }} flex items-start justify-between gap-4 rounded-2xl">
        <div class="min-w-0">
            <p class="text-sm font-medium text-arang/80">{{ $label }}</p>
            <p class="mt-1 font-judul text-4xl font-semibold">{{ $value }}</p>
            @if ($deskripsi)
                <p class="mt-1 text-xs leading-relaxed text-arang/60">{{ $deskripsi }}</p>
            @endif
        </div>
        @if ($ikon)
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $chip }}">
                <x-icon :name="$ikon" />
            </span>
        @endif
    </div>
@endif