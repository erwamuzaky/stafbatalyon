@props(['node', 'tingkat' => 1])

@php
    $profil = $node['profil'];
    $anak = $node['anak'];
    $puncak = $tingkat === 1;

    // Inisial dipakai kalau staf belum punya foto.
    $kata = preg_split('/\s+/', trim($profil->user?->name ?? '')) ?: [];
    $pertama = $kata[0] ?? '';
    $terakhir = count($kata) > 1 ? end($kata) : '';
    $inisial = mb_strtoupper(mb_substr($pertama, 0, 1) . ($terakhir !== $pertama ? mb_substr($terakhir, 0, 1) : ''));

    $warna = [
        'bg-laut-100 text-laut-700',
        'bg-emas-100 text-emas-700',
        'bg-info-100 text-info-700',
        'bg-sukses-100 text-sukses-700',
        'bg-peringatan-100 text-peringatan-700',
        'bg-marun-100 text-marun-700',
    ];
    $index = abs(crc32(mb_strtolower($profil->user?->name ?? ''))) % count($warna);
@endphp

<li>
    {{-- Simpul kartu foto: foto memenuhi seluruh kartu (rasio 3:4), nama dan
         jabatan menempel di atasnya lewat lapisan gradasi gelap di bawah.
         Belum ada foto: bidang warna dengan inisial besar. Tingkat puncak:
         kartu sedikit lebih lebar + bingkai emas. --}}
    <div @class([
        'w-full overflow-hidden rounded-2xl border bg-white shadow-soft-sm md:w-36 md:transition md:duration-200 lg:w-40',
        'border-laut-100' => ! $puncak,
        'border-emas-300 md:w-40 md:shadow-soft-md lg:w-44' => $puncak,
    ])>
        <div class="relative aspect-[3/4] w-full">
            @if ($profil->foto_url)
                <img src="{{ $profil->foto_url }}" alt="Foto {{ $profil->user?->name }}" class="absolute inset-0 h-full w-full object-cover">
            @elseif ($inisial !== '')
                <div @class(['absolute inset-0 flex items-center justify-center', $warna[$index]]) aria-hidden="true">
                    <span class="font-judul text-5xl font-bold">{{ $inisial }}</span>
                </div>
                <span class="sr-only">{{ $profil->user?->name }}</span>
            @else
                <div class="absolute inset-0 flex items-center justify-center bg-laut-50" aria-hidden="true">
                    <x-icon name="profil" class="h-12 w-12 text-laut-300" />
                </div>
            @endif

            {{-- Gradasi gelap di bawah: teks tetap terbaca di atas foto apa pun. --}}
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 via-black/45 to-transparent px-2.5 pb-2 pt-8" aria-hidden="true"></div>

            <div class="absolute inset-x-0 bottom-0 px-2.5 pb-2">
                @if ($profil->jabatan)
                    <p class="truncate text-[10px] font-semibold uppercase leading-tight tracking-wide text-white/85">{{ $profil->jabatan }}</p>
                @endif
                <p class="truncate text-xs font-semibold leading-tight text-white" title="{{ $profil->user?->name }}">{{ $profil->user?->name }}</p>
                @if ($profil->kelas)
                    <p class="truncate text-[10px] leading-tight text-white/75">Kelas {{ $profil->kelas }}</p>
                @endif
            </div>
        </div>
    </div>

    @if ($anak->isNotEmpty())
        <ul>
            @foreach ($anak as $simpulAnak)
                <x-bagan-node :node="$simpulAnak" :tingkat="$tingkat + 1" />
            @endforeach
        </ul>
    @endif
</li>
