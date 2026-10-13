@props([
    'type' => 'sukses',
])

@php
    $peta = match ($type) {
        'error' => ['border-bahaya-200 text-bahaya-700', 'tolak'],
        'peringatan' => ['border-peringatan-200 text-peringatan-700', 'bintang'],
        default => ['border-sukses-200 text-sukses-700', 'setuju'],
    };
@endphp

<div x-data="{ buka: true }" x-cloak x-show="buka"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-1"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     x-init="setTimeout(() => buka = false, 5000)"
     role="status"
     {{ $attributes->merge(['class' => 'pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border bg-white px-4 py-3 text-sm shadow-soft-lg ' . $peta[0]]) }}>
    <x-icon :name="$peta[1]" class="mt-0.5 h-5 w-5" />
    <div class="min-w-0 flex-1">{{ $slot }}</div>
    <button type="button" @click="buka = false"
            class="-mx-1 -my-1 shrink-0 rounded-md p-2 opacity-60 hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-current">
        <span class="sr-only">Tutup</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>