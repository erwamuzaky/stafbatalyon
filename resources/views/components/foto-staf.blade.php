@props([
    'profil' => null,
    'nama' => '',
    'ajakan' => 'Belum ada foto. Pasang foto supaya kartumu di bagan struktur tidak polos.',
])

@php
    // Inisial dipakai kalau staf belum punya foto (pola sama seperti bagan-node).
    $kata = preg_split('/\s+/', trim($nama)) ?: [];
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
    $index = abs(crc32(mb_strtolower($nama))) % count($warna);

    $fotoUrl = $profil?->foto_url;
    $idFoto = 'foto-staf-'.($profil?->id ?? 'baru');
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:items-start sm:gap-5']) }}
     x-data="{
        pratinjau: null,
        namaBerkas: '',
        memproses: false,
        pilih(e) {
            const berkas = e.target.files && e.target.files[0];
            if (!berkas) { this.buang(); return; }
            if (this.pratinjau) URL.revokeObjectURL(this.pratinjau);
            this.pratinjau = URL.createObjectURL(berkas);
            this.namaBerkas = berkas.name;
        },
        buang() {
            if (this.pratinjau) URL.revokeObjectURL(this.pratinjau);
            this.pratinjau = null;
            this.namaBerkas = '';
            if (this.$refs.input) this.$refs.input.value = '';
        }
     }">
    {{-- Pratinjau persegi: foto baru (dipilih), foto lama, atau inisial nama --}}
    <div class="relative aspect-square w-32 shrink-0 self-center overflow-hidden rounded-2xl border border-laut-100 bg-laut-50 sm:self-start">
        <template x-if="pratinjau">
            <img :src="pratinjau" alt="Pratinjau foto baru" class="h-full w-full object-cover">
        </template>
        <template x-if="!pratinjau">
            <div class="h-full w-full">
                @if ($fotoUrl)
                    <img src="{{ $fotoUrl }}" alt="Foto {{ $nama }}" class="h-full w-full object-cover">
                @elseif ($inisial !== '')
                    <div class="flex h-full w-full items-center justify-center font-judul text-4xl font-bold {{ $warna[$index] }}">
                        <span aria-hidden="true">{{ $inisial }}</span>
                        <span class="sr-only">{{ $nama }}</span>
                    </div>
                @else
                    <div class="flex h-full w-full items-center justify-center text-laut-400">
                        <x-icon name="profil" class="h-10 w-10" />
                    </div>
                @endif
            </div>
        </template>
    </div>

    <div class="min-w-0 flex-1 space-y-3">
        @if ($profil)
            @unless ($fotoUrl)
                <p class="text-sm leading-relaxed text-gray-600">{{ $ajakan }}</p>
            @endunless

            <form method="POST" action="{{ route('profile.photo.update') }}"
                  enctype="multipart/form-data"
                  @submit="memproses = true" class="space-y-2">
                @csrf
                @method('patch')

                <input id="{{ $idFoto }}" x-ref="input" name="foto" type="file" accept="image/*"
                       class="peer sr-only" @change="pilih($event)" required>
                <label for="{{ $idFoto }}"
                       class="btn btn-sekunder cursor-pointer peer-focus-visible:ring-2 peer-focus-visible:ring-laut-500 peer-focus-visible:ring-offset-2">
                    {{ $fotoUrl ? 'Ganti foto' : 'Pilih foto' }}
                </label>

                <p x-cloak x-show="namaBerkas" class="truncate text-sm text-arang">
                    <span x-text="namaBerkas"></span>
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <x-primary-button x-bind:disabled="memproses || ! pratinjau">
                        <span x-show="!memproses">Simpan foto</span>
                        <span x-show="memproses" x-cloak>Menyimpan...</span>
                    </x-primary-button>

                    <button type="button" x-cloak x-show="pratinjau" @click="buang()"
                            class="text-sm font-medium text-marun-600 underline decoration-marun-200 underline-offset-2 hover:text-marun-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-marun-500">
                        Buang pilihan
                    </button>
                </div>

                <p class="text-xs text-gray-500">Maksimal 2 MB. Format jpg, png, atau webp.</p>
                <x-input-error :messages="$errors->get('foto')" />
            </form>

            @if ($fotoUrl)
                <x-konfirmasi-hapus :action="route('profile.photo.destroy')" judul="foto profil"
                                    pesan="Foto ini akan hilang dari kartu profil dan bagan struktur."
                                    teks="Hapus foto" />
            @endif
        @else
            <p class="text-sm leading-relaxed text-gray-600">
                Profil staf belum lengkap, jadi foto belum bisa dipasang. Hubungi admin untuk melengkapi data Anda.
            </p>
        @endif
    </div>
</div>
