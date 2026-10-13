@props([
    'href' => null,
    'ikon' => 'ubah',
    'label' => 'Aksi',
    'bahaya' => false,
])

@php
    $kelas = 'ikon-aksi' . ($bahaya ? ' ikon-aksi-bahaya' : '');
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $kelas }}" title="{{ $label }}" aria-label="{{ $label }}">
        <x-icon :name="$ikon" />
    </a>
@else
    <button type="submit" {{ $attributes->merge(['class' => $kelas]) }} title="{{ $label }}" aria-label="{{ $label }}">
        <x-icon :name="$ikon" />
    </button>
@endif
