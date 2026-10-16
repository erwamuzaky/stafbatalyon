@props(['kunci', 'label', 'gambar' => null, 'flagHapus'])

<div>
    <x-input-label for="{{ $kunci }}" :value="$label" />

    <div class="mt-1">
        @if ($gambar)
            <div class="mb-2">
                <img src="{{ $gambar }}" alt="{{ $label }}" class="max-w-xs rounded border">
            </div>
        @endif

        <input type="file" id="{{ $kunci }}" name="{{ $kunci }}" accept="image/*"
               class="input-baku block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-laut-50 file:text-laut-700 hover:file:bg-laut-100">
    </div>

    <x-input-error :messages="$errors->get($kunci)" class="mt-2" />
</div>

@if ($gambar)
    <div class="flex items-center">
        <input type="hidden" name="{{ $flagHapus }}" value="0">
        <input type="checkbox" id="{{ $flagHapus }}" name="{{ $flagHapus }}" value="1"
               class="rounded border-gray-300 text-laut-600 shadow-sm focus:ring-laut-500">
        <label for="{{ $flagHapus }}" class="ml-2 text-sm text-gray-600">Hapus background saat ini</label>
    </div>
@endif
