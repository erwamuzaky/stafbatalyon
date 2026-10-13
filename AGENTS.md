# Staf Batalyon SMKN 3 Pariaman

Web profil dan manajemen Staf Batalyon (setara OSIS) SMKN 3 Pariaman.
Bahasa antarmuka: Bahasa Indonesia. Timezone: Asia/Jakarta.

## Stack
- Laravel 12, PHP 8.3+, MySQL
- Blade + Tailwind (Laravel Breeze, varian blade)
- barryvdh/laravel-dompdf untuk cetak absensi
- Upload file lewat Storage Laravel (disk `public`)

## Role
- `admin`: sekretaris/bendahara (staf inti). Boleh banyak akun admin.
- `staf`: staf biasa yang baru ditunjuk.

## Aturan bisnis (WAJIB)
1. Daftar hanya untuk staf: user baru otomatis `role=staf`, `status=pending`.
2. Setelah daftar, user TIDAK otomatis login. Arahkan ke halaman "menunggu persetujuan admin".
3. Hanya akun `status=approved` yang boleh login. `pending` dan `rejected` ditolak dengan pesan jelas.
4. Admin yang menyetujui/menolak akun, mengelola data staf dan bidang.
5. Arsip proposal event: admin menginput proposal tiap event. Event baru bisa memakai
   proposal lama sebagai dasar (tombol "Gunakan sebagai dasar", isi `template_dari_id`).
   Kalau formatnya beda, upload proposal baru dari nol.
6. Pembagian piket gerbang dibuat admin; staf hanya melihat jadwalnya sendiri.
7. Absensi HANYA untuk dicetak (PDF). Tidak ada fitur absen online.
8. Staf awalnya hanya punya dashboard (jadwal piket sendiri + info dasar).

## Database
- users: name, email, password, role, status
- bidangs: nama
- staf_profiles: user_id, bidang_id, jabatan, is_inti, no_hp, foto
- events: nama, tahun, tanggal_mulai, tanggal_selesai, status
- proposals: event_id, judul, file_path, catatan, template_dari_id (nullable)
- piket_gerbang: tanggal, sesi, user_id, catatan

## Struktur
- Controller admin di `app/Http/Controllers/Admin`, staf di `.../Staf`
- Middleware: `EnsureRole`, `EnsureApproved`
- View: `resources/views/admin`, `staf`, `pdf`
- Route dikelompokkan `/admin` dan `/staf`

## Konvensi
- Ikuti konvensi Laravel; pakai Form Request untuk validasi.
- Nama tabel/kolom bahasa Indonesia seperti di atas; kode (class, method) bahasa Inggris.
- Satu fase per sesi; jalankan `php artisan migrate` dan cek route sebelum lanjut.
- Jangan menambah fitur di luar PLAN.md tanpa konfirmasi.
