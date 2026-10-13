@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm font-medium text-sukses-600']) }}>
        {{ $status }}
    </div>
@endif
