---
name: staf-batalyon
description: Panduan kerja project Staf Batalyon (Laravel 12 + Blade + Tailwind v3, Breeze, dompdf). Pakai skill ini setiap mengerjakan fitur, perbaikan bug, view Blade, cetak absensi PDF, migration, atau test di project staf-batalyon.
---

# Staf Batalyon - Panduan Kerja

Web profil dan manajemen Staf Batalyon (setara OSIS) SMKN 3 Pariaman. Bahasa antarmuka: Bahasa Indonesia. Timezone: Asia/Jakarta.

Aturan bisnis lengkap ada di `AGENTS.md` project. Baca itu dulu, jangan berasumsi.

## Perintah penting
- Test: `php artisan test` (atau `./vendor/bin/phpunit`). Test pakai SQLite di memori, jadi database MySQL `staf_batalyon` tidak akan terhapus.
- Format kode: `./vendor/bin/pint`
- Aset frontend: `npm run build` (Vite). Mode dev: `npm run dev`.
- Nyalakan semua sekaligus: `composer dev` (server + queue + pail + vite).

## Aturan yang tidak boleh dilanggar
- Laravel 12 / PHP 8.2. Jangan pakai fitur atau paket yang mewajibkan PHP 8.3+.
- Validasi WAJIB lewat Form Request di `app/Http/Requests/...`, bukan di dalam controller.
- Nama tabel dan kolom pakai bahasa Indonesia; nama class dan method pakai bahasa Inggris.
- Satu fase per sesi. Setelah mengubah skema, jalankan `php artisan migrate`, lalu cek route.
- Jangan menambah fitur di luar `PLAN.md` tanpa konfirmasi.

## Peta file (ikuti yang sudah ada, jangan bikin pola baru)
- Controller admin: `app/Http/Controllers/Admin/`
- Controller staf: `app/Http/Controllers/Staf/`
- Model: `app/Models/` — User, Bidang, StafProfile, Event, Proposal, PiketGerbang
- Form Request: `app/Http/Requests/Admin/`
- Middleware: `EnsureRole`, `EnsureApproved` (sudah terdaftar)
- View admin: `resources/views/admin/...`, staf: `resources/views/staf/...`, PDF: `resources/views/pdf/...`
- Route: `routes/web.php`, dikelompokkan `/admin` dan `/staf`
- Test fitur: `tests/Feature/Admin/...`, `tests/Feature/Staf/...`, `tests/Feature/Auth/...`

## Tampilan
- WAJIB baca dulu `C:/Users/user/.config/opencode/ui-guidelines.md` sebelum menyentuh tampilan.
- Pakai komponen Blade yang sudah ada, jangan tulis ulang blok HTML. Yang tersedia antara lain: `card`, `badge`, `stat`, `empty`, `modal`, `konfirmasi-hapus`, `tabel-responsif`, `page-header`, `toast`, `icon`, `avatar`, `alert`, `nav-link`, dan tombol `primary-button` / `secondary-button` / `danger-button`.
- Layout: `admin-layout` dan `staf-layout`.
- Warna pakai token tema di `tailwind.config.js`, jangan hardcode hex:
  - Utama: `laut`. Aksen (dipakai hemat): `emas`. Latar lembut: `krem`, halaman `tulang`, teks `arang`, bahaya `marun`.
  - Status: `info`, `sukses`, `peringatan`, `bahaya`.
- Font: judul dan angka penting `judul` (Barlow Semi Condensed), teks isi `isi` (Public Sans).
- `content` Tailwind sengaja hanya memindai `resources/**` supaya kelas lama dari view terkompilasi tidak ikut terbawa. Jangan ubah ini tanpa alasan jelas.

## Cetak absensi (PDF)
- Pakai `barryvdh/laravel-dompdf`. View PDF ada di `resources/views/pdf/`.
- Absensi hanya untuk dicetak; tidak ada fitur absen online.

## Upload file
- Lewat Storage Laravel, disk `public`.

## Saat melapor
- Jalankan test yang relevan dulu. Untuk perubahan besar, sekalian `php artisan test` penuh + `./vendor/bin/pint`.
- Sebut hal yang cuma bisa dicek di browser (tampilan, responsive, mode gelap) supaya user mengeceknya.
