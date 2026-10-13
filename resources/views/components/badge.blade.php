@props(['status' => 'selesai', 'label' => null])

@php
    $peta = [
        'menunggu' => ['peringatan', 'Menunggu'],
        'disetujui' => ['sukses', 'Disetujui'],
        'selesai' => ['sukses', 'Selesai'],
        'ditolak' => ['bahaya', 'Ditolak'],
        'rencana' => ['info', 'Rencana'],
        'berjalan' => ['laut', 'Berjalan'],
    ];
    [$varian, $fiturBawaan] = $peta[$status] ?? ['selesai', $status];

    $kelas = match ($varian) {
        'peringatan' => 'bg-peringatan-50 text-peringatan-700 ring-peringatan-200',
        'bahaya' => 'bg-bahaya-50 text-bahaya-700 ring-bahaya-200',
        'info' => 'bg-info-50 text-info-700 ring-info-200',
        'laut' => 'bg-laut-50 text-laut-700 ring-laut-200',
        default => 'bg-sukses-50 text-sukses-700 ring-sukses-200',
    };
    $titik = match ($varian) {
        'peringatan' => 'bg-peringatan-500',
        'bahaya' => 'bg-bahaya-500',
        'info' => 'bg-info-500',
        'laut' => 'bg-laut-500',
        default => 'bg-sukses-500',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold whitespace-nowrap ring-1 ring-inset ' . $kelas]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $titik }}" aria-hidden="true"></span>
    {{ $label ?? $fiturBawaan }}
</span>