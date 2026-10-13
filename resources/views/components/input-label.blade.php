@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-arang']) }}>
    {{ $value ?? $slot }}
</label>
