@props([
    'title' => 'Belum ada data',
    'ikon' => 'dokumen',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-2xl border border-dashed border-laut-200 bg-laut-50/40 px-6 py-12 text-center']) }}>
    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-laut-100 text-laut-500">
        <x-icon :name="$ikon" class="h-8 w-8" />
    </div>
    <h3 class="mt-4 font-judul text-lg font-semibold text-laut-900">{{ $title }}</h3>
    <div class="mt-1 text-sm text-gray-500">
        {{ $slot }}
    </div>
    @if (isset($action))
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>
