@props(['kepekatan' => 'sedang'])

{{--
    Lapisan gelap (scrim) di atas foto latar bagian beranda dan halaman struktur.
    Tingkat kegelapannya diatur admin lewat pengaturan "latar_kepekatan".
    Kelas gradien sengaja ditulis sebagai string literal di sini supaya Tailwind
    ikut memindai dan benar-benar membuat kelasnya.
--}}
@php
    $kelas = match ($kepekatan) {
        'samar' => 'from-laut-900/70 via-laut-900/45 to-laut-900/65',
        'tegas' => 'from-laut-900/90 via-laut-900/70 to-laut-900/85',
        default => 'from-laut-900/80 via-laut-900/55 to-laut-900/75',
    };
@endphp

<div @class(['absolute inset-0 bg-gradient-to-b', $kelas]) aria-hidden="true"></div>
