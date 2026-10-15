@props(['node', 'tampilBidang' => false])

@php
    $profil = $node['profil'];
    $anak = $node['anak'];

    // Inisial dipakai kalau staf belum punya foto (pola sama seperti komponen kartu-staf).
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
    <div class="flex w-28 flex-col items-center rounded-2xl border border-laut-100 bg-white p-2 sm:w-32 sm:p-2.5">
        <div class="relative flex aspect-[4/5] w-full items-center justify-center overflow-hidden rounded-xl bg-laut-50">
            @if ($profil->foto_url)
                <img src="{{ $profil->foto_url }}" alt="Foto {{ $profil->user?->name }}" class="h-full w-full object-cover">
            @elseif ($inisial !== '')
                <span aria-hidden="true" class="font-judul text-2xl font-bold {{ $warna[$index] }}">{{ $inisial }}</span>
                <span class="sr-only">{{ $profil->user?->name }}</span>
            @else
                <x-icon name="profil" class="h-8 w-8 text-laut-400" />
            @endif
        </div>

        <div class="mt-2 w-full text-center">
            @if ($profil->jabatan)
                <p class="font-judul text-sm font-bold leading-tight text-laut-800">{{ $profil->jabatan }}</p>
            @endif
            <p class="mt-0.5 text-xs leading-tight text-arang sm:text-sm">{{ $profil->user?->name }}</p>
            @if ($profil->kelas)
                <p class="mt-0.5 text-xs leading-tight text-gray-500">Kelas {{ $profil->kelas }}</p>
            @endif
            @if ($tampilBidang && $profil->bidang)
                <span class="mt-1 inline-block rounded-full bg-laut-50 px-2 py-0.5 text-[10px] font-medium leading-tight text-laut-700 sm:text-xs">{{ $profil->bidang->nama }}</span>
            @endif
        </div>
    </div>

    @if ($anak->isNotEmpty())
        <ul>
            @foreach ($anak as $simpulAnak)
                <x-bagan-node :node="$simpulAnak" :tampil-bidang="$tampilBidang" />
            @endforeach
        </ul>
    @endif
</li>
