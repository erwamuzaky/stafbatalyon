@props(['id' => 'pola-bintang'])

{{--
    Motif bintang halus dari logo Staf Batalyon.
    Dipakai sebagai latar panel laut, warnai lewat class text-white/[0.08].
--}}
<div {{ $attributes->merge(['class' => 'pointer-events-none absolute inset-0 h-full w-full overflow-hidden']) }} aria-hidden="true">
    <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="{{ $id }}" width="44" height="44" patternUnits="userSpaceOnUse">
                <path transform="translate(13 13) scale(0.8)" fill="currentColor"
                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#{{ $id }})" />
    </svg>
</div>
