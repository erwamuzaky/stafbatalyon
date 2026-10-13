@props([
    'type' => 'info',
    'dismissible' => true,
    'autoDismiss' => null,
])

@php
    $peta = match ($type) {
        'sukses' => ['border-sukses-200 bg-sukses-50 text-sukses-800', 'setuju'],
        'error' => ['border-bahaya-200 bg-bahaya-50 text-bahaya-700', 'tolak'],
        'peringatan' => ['border-peringatan-200 bg-peringatan-50 text-peringatan-700', 'bintang'],
        default => ['border-info-200 bg-info-50 text-info-800', 'bintang'],
    };
@endphp

<div x-data="{ buka: true }" x-show="buka" x-cloak
     x-transition
     x-init="@if ($autoDismiss) setTimeout(() => buka = false, {{ (int) $autoDismiss }}) @endif"
     role="status"
     {{ $attributes->merge(['class' => 'flex items-start justify-between gap-3 rounded-2xl border px-4 py-3 text-sm ' . $peta[0]]) }}>
    <div class="flex items-start gap-3">
        <x-icon :name="$peta[1]" class="mt-0.5 h-5 w-5" />
        <div class="min-w-0">{{ $slot }}</div>
    </div>

    @if ($dismissible)
        <button type="button" @click="buka = false"
                class="-mx-1 -my-1 shrink-0 rounded-md p-2 opacity-70 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-current">
            <span class="sr-only">Tutup</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>