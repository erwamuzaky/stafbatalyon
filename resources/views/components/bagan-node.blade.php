@props(['node'])

@php
    $profil = $node['profil'];
    $anak = $node['anak'];

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
    {{-- Satu kartu: foto penuh, keterangan menempel di dasar foto --}}
    <div class="relative w-44 shrink-0 overflow-hidden rounded-2xl border border-laut-100 bg-white shadow-soft-md transition duration-200 sm:w-48">
        <div class="relative aspect-[4/5] w-full bg-laut-50">
            @if ($profil->foto_url)
                <img src="{{ $profil->foto_url }}" alt="Foto {{ $profil->user?->name }}" class="h-full w-full object-cover">
            @elseif ($inisial !== '')
                <span aria-hidden="true" class="flex h-full w-full items-center justify-center font-judul text-4xl font-bold {{ $warna[$index] }}">{{ $inisial }}</span>
                <span class="sr-only">{{ $profil->user?->name }}</span>
            @else
                <span class="flex h-full w-full items-center justify-center text-laut-400">
                    <x-icon name="profil" class="h-10 w-10" />
                </span>
            @endif

            {{-- Keterangan menempel di dasar foto, dibungkus lapisan gelap transparan supaya tetap terbaca --}}
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-arang via-arang/80 to-transparent px-3 pb-3 pt-10">
                @if ($profil->jabatan)
                    <p class="font-judul text-[11px] font-semibold uppercase leading-tight tracking-wide text-emas-300">{{ $profil->jabatan }}</p>
                @endif
                <p class="mt-1 text-sm font-semibold leading-tight text-white" title="{{ $profil->user?->name }}">{{ $profil->user?->name }}</p>
                @if ($profil->kelas)
                    <p class="mt-0.5 text-[11px] leading-tight text-white/80">Kelas {{ $profil->kelas }}</p>
                @endif
            </div>
        </div>
    </div>

    @if ($anak->isNotEmpty())
        <ul>
            @foreach ($anak as $simpulAnak)
                <x-bagan-node :node="$simpulAnak" />
            @endforeach
        </ul>
    @endif
</li>
