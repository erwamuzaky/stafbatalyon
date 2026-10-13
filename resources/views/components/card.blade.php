@props(['title' => null])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-laut-100 bg-white shadow-soft-sm overflow-hidden']) }}>
    @if ($title || isset($header))
        <div class="border-b border-laut-50 px-5 py-4 sm:px-6">
            @if ($title)
                <h3 class="font-judul text-lg font-semibold text-laut-800">{{ $title }}</h3>
            @else
                {{ $header }}
            @endif
        </div>
    @endif
    <div class="p-5 sm:p-6">
        {{ $slot }}
    </div>
    @if (isset($footer))
        <div class="border-t border-laut-50 bg-laut-50/30 px-5 py-3 sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
