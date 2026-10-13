# PLAN.md - Prompt per Fase untuk OpenCode

Cara pakai: baca AGENTS.md dulu, lalu kerjakan satu fase per sesi.
Salin prompt fase ke OpenCode, tunggu selesai, tes manual, baru lanjut.

## Fase 0 - Setup (manual di terminal)
```bash
composer create-project laravel/laravel staf-batalyon
cd staf-batalyon
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require barryvdh/laravel-dompdf
php artisan storage:link
```
Atur `.env`: DB, `APP_NAME`, `APP_TIMEZONE=Asia/Jakarta`, `APP_LOCALE=id`.
Taruh AGENTS.md dan PLAN.md ini di root project.

## Fase 1 - Database dan seeder
> Baca AGENTS.md. Buat migrasi, model (dengan relasi), dan seeder untuk tabel:
> users (tambah kolom role, status), bidangs, staf_profiles, events, proposals,
> piket_gerbang. Buat BidangSeeder (Sekretaris, Bendahara, Humas, dst. sebagai contoh)
> dan AdminSeeder (1 akun admin approved). Jalankan migrate --seed lalu pastikan sukses.

## Fase 2 - Auth dan persetujuan
> Modifikasi Breeze: registrasi membuat user role=staf, status=pending, tanpa auto-login,
> lalu redirect ke halaman "menunggu persetujuan". Buat middleware EnsureApproved
> (tolak pending/rejected saat login dengan pesan jelas) dan EnsureRole. Daftarkan di
> bootstrap/app.php dan buat grup route /admin dan /staf. Tambahkan test sederhana.

## Fase 3 - Admin: dashboard, akun, staf, bidang
> Buat Admin/DashboardController (ringkasan: jumlah staf, pendaftar pending, event,
> piket minggu ini), AkunController (setujui/tolak, nonaktifkan, reset password),
> StafController dan BidangController (CRUD, tampilkan nama staf + bidang + status inti).
> Buat layout admin dengan sidebar dan view-nya.

## Fase 4 - Event dan arsip proposal
> Buat Admin/EventController dan ProposalController. Upload proposal (pdf/doc/docx),
> daftar arsip per tahun dan event, pencarian, dan tombol "Gunakan sebagai dasar" yang
> membuat proposal baru dengan template_dari_id terisi dan salinan file. Boleh juga
> upload proposal baru dari nol.

## Fase 5 - Piket gerbang
> Buat Admin/PiketController: admin membagi staf per tanggal dan sesi, bisa edit dan hapus,
> cegah duplikat staf pada slot yang sama. Tampilan kalender/tabel mingguan.

## Fase 6 - Cetak absensi PDF
> Buat Admin/AbsensiController: form pilih nama kegiatan, tanggal, dan filter
> (semua / staf inti / per bidang), lalu generate PDF dengan DomPDF memakai
> resources/views/pdf/absensi.blade.php: kop (SMKN 3 Pariaman), tabel No, Nama, Bidang,
> kolom tanda tangan, dan area tanda tangan sekretaris. Format A4. Tanpa absen online.

## Fase 7 - Dashboard staf dan halaman profil
> Buat Staf/DashboardController dan view: sapaan, bidang, jadwal piket miliknya.
> Rapikan welcome.blade.php menjadi halaman profil Staf Batalyon dengan tombol Login/Daftar.
> Polesan tampilan responsif dengan Tailwind.

## Checklist akhir
- [ ] Daftar -> pending -> tidak bisa login -> admin setujui -> bisa login
- [ ] Staf tidak bisa membuka /admin
- [ ] Proposal lama bisa dijadikan dasar proposal baru
- [ ] PDF absensi tercetak rapi di A4
