<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-utama']) }}>
    {{ $slot }}
</button>
