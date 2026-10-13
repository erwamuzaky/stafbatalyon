{{--
    Hiasan panel kiri halaman auth.
    Satu bintang besar terpotong di pojok kanan bawah, ditambah beberapa
    bintang kecil tersebar tidak beraturan. Tanpa kisi berulang.
    Warna mengikuti currentColor (mis. text-white).
--}}
<div {{ $attributes->merge(['class' => 'pointer-events-none absolute inset-0 overflow-hidden']) }} aria-hidden="true">
    <svg class="h-full w-full" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid slice"
         fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <path id="bintang-auth"
                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
        </defs>

        {{-- Bintang besar, terpotong pinggir kanan bawah --}}
        <use href="#bintang-auth" transform="translate(158 158) rotate(12) scale(9.5)" opacity="0.07" />

        {{-- Bintang kecil tersebar tidak beraturan --}}
        <use href="#bintang-auth" transform="translate(22 26) scale(1.1)" opacity="0.10" />
        <use href="#bintang-auth" transform="translate(66 34) scale(0.7)" opacity="0.08" />
        <use href="#bintang-auth" transform="translate(118 20) scale(0.9)" opacity="0.09" />
        <use href="#bintang-auth" transform="translate(30 94) scale(1.3)" opacity="0.07" />
        <use href="#bintang-auth" transform="translate(80 130) scale(0.8)" opacity="0.10" />
        <use href="#bintang-auth" transform="translate(138 98) scale(1)" opacity="0.08" />
        <use href="#bintang-auth" transform="translate(18 166) scale(0.9)" opacity="0.09" />
        <use href="#bintang-auth" transform="translate(102 176) scale(1.1)" opacity="0.07" />
    </svg>
</div>
