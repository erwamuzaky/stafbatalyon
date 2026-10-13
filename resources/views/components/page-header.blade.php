@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between sm:gap-4']) }}>
    <div>
        <h1 class="font-judul text-2xl font-bold tracking-tight text-laut-900 sm:text-3xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
