import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // Hanya pindai sumber asli. Jangan pindai storage/framework/views supaya
    // kelas lama dari view yang sudah dikompilasi tidak ikut terbawa.
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Warna utama: laut, mengikuti logo Staf Batalyon.
                laut: {
                    50: '#F2F6F9',
                    100: '#E4ECF2',
                    200: '#C6D8E3',
                    300: '#A0BFD0',
                    400: '#6F9AB4',
                    500: '#47748F',
                    600: '#1F4E6B',
                    700: '#1B435E',
                    800: '#16364D',
                    900: '#112A3D',
                },
                // Warna aksen: emas, dipakai hemat (garis, menu aktif, lencana).
                emas: {
                    50: '#FAF6E9',
                    100: '#F4EAC8',
                    200: '#E8D490',
                    300: '#D7BC5C',
                    400: '#C5A53F',
                    500: '#B8962E',
                    600: '#987A25',
                    700: '#7A611C',
                },
                // Warna netral hangat: krem, dipakai untuk latar lembut.
                krem: {
                    50: '#FCFBF7',
                    100: '#F7F3E9',
                    200: '#EFE7D3',
                    300: '#E2D4B3',
                    500: '#C9B584',
                    600: '#B09A68',
                    700: '#8F7C52',
                },
                // Latar halaman.
                tulang: '#F7F6F2',
                // Teks utama.
                arang: '#1F2523',
                // Bahaya/hapus.
                marun: {
                    50: '#F9EEEF',
                    100: '#F0D6D8',
                    200: '#DFABAF',
                    300: '#C97F85',
                    400: '#AA555D',
                    500: '#8A2F38',
                    600: '#7A2931',
                    700: '#62212A',
                    800: '#4B1A20',
                    900: '#361317',
                },
                // Palet pastel untuk status. Skala lengkap supaya semua kelas
                // border-/bg-/text- yang dipakai benar-benar tersedia.
                info: {
                    50: '#F0F9FF',
                    100: '#E0F2FE',
                    200: '#BAE6FD',
                    300: '#7DD3FC',
                    500: '#0EA5E9',
                    600: '#0284C7',
                    700: '#075985',
                    800: '#0C4A6E',
                },
                sukses: {
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    300: '#6EE7B7',
                    500: '#10B981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065F46',
                },
                peringatan: {
                    50: '#FFFBEB',
                    100: '#FEF3C7',
                    200: '#FDE68A',
                    300: '#FCD34D',
                    500: '#F59E0B',
                    600: '#D97706',
                    700: '#B45309',
                    800: '#92400E',
                },
                bahaya: {
                    50: '#FEF2F2',
                    100: '#FEE2E2',
                    200: '#FECACA',
                    300: '#FCA5A5',
                    500: '#EF4444',
                    600: '#DC2626',
                    700: '#B91C1C',
                    800: '#991B1B',
                },
            },
            boxShadow: {
                'soft-sm': '0 1px 2px rgba(31, 78, 107, 0.04)',
                'soft-md': '0 4px 12px rgba(31, 78, 107, 0.06)',
                'soft-lg': '0 10px 30px rgba(31, 78, 107, 0.10)',
                'soft-xl': '0 20px 40px rgba(31, 78, 107, 0.12)',
            },
            fontFamily: {
                // Judul dan angka penting: Barlow Semi Condensed.
                judul: ['"Barlow Semi Condensed"', 'sans-serif'],
                // Teks isi: Public Sans.
                isi: ['"Public Sans Variable"', ...defaultTheme.fontFamily.sans],
                sans: ['"Public Sans Variable"', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};