@props(['headers' => []])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-laut-100 bg-white shadow-soft-sm']) }}>
    {{-- Tabel untuk desktop --}}
    <div class="hidden overflow-x-auto md:block">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-laut-100 bg-laut-50/50">
                <tr>
                    @foreach ($headers as $header)
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-laut-800">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-laut-50">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    {{-- Daftar kartu bertumpuk untuk HP --}}
    @isset($mobile)
        <div class="grid gap-3 p-4 md:hidden">
            {{ $mobile }}
        </div>
    @endisset
</div>
