import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
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
                    800: '#5C4814',
                    900: '#40320E',
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
                // Palet pastel untuk status
                info: { 50: '#F0F9FF', 700: '#075985', 800: '#0C4A6E' },
                sukses: { 50: '#ECFDF5', 700: '#047857', 800: '#065F46' },
                peringatan: { 50: '#FEFCE8', 700: '#854D0E', 800: '#713F12' },
                bahaya: { 50: '#FEF2F2', 700: '#991B1B', 800: '#7F1D1D' },
            },
            boxShadow: {
                'soft-sm': '0 1px 2px rgba(31, 78, 107, 0.04)',
                'soft-md': '0 4px 12px rgba(31, 78, 107, 0.06)',
                'soft-lg': '0 10px 30px rgba(31, 78, 107, 0.10)',
                'soft-xl': '0 20px 40px rgba(31, 78, 107, 0.12)',
            },
            borderRadius: {
                'xl': '0.875rem',
                '2xl': '1.25rem',
                '3xl': '1.5rem',
            },
            transitionDuration: {
                150: '150ms',
                200: '200ms',
            },
            lineHeight: {
                relaxed: '1.75rem',
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