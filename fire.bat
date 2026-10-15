@echo off
setlocal
cd /d "%~dp0"

REM ===================== PENGATURAN =====================
REM Nyalain aplikasi Staf Batalyon TANPA vite (server vite tidak dipakai).
REM Yang jalan barengan dalam satu jendela: web server, queue worker, log, dan auto-build aset.
REM Ubah di sini kalau perlu.
set "HOST=127.0.0.1"
set "PORT=8000"
set "APP_URL=http://127.0.0.1:%PORT%"
REM ======================================================

echo === Staf Batalyon : jalankan aplikasi tanpa vite ===
echo.

where php >nul 2>nul
if errorlevel 1 (
    echo [X] PHP tidak ada di PATH. Pasang PHP dulu.
    pause
    exit /b 1
)

if not exist "vendor\autoload.php" (
    echo [X] Folder vendor belum ada. Jalankan dulu: composer install
    pause
    exit /b 1
)

if not exist "node_modules\.bin\concurrently.cmd" (
    echo [X] Paket concurrently belum ada. Jalankan dulu: npm install
    pause
    exit /b 1
)

if not exist "public\build\manifest.json" (
    echo [!] Aset hasil build belum ada di public\build.
    echo     Karena vite tidak dipakai, tampilan bisa polos tanpa CSS/JS.
    choice /c YN /m "Bangun aset sekarang dengan npm run build"
    if errorlevel 2 (
        echo     Dilewati. Jangan lupa jalankan "npm run build" sebelum buka web.
        echo.
    ) else (
        echo     Membangun aset ...
        call npm run build
        echo.
    )
)

echo [1/2] Membersihkan cache config / route / view ...
php artisan optimize:clear
echo.

echo [2/2] Memastikan migrasi database terbaru ...
php artisan migrate --graceful
echo.

echo Menyalakan server, queue, log, dan auto-build aset (tanpa vite dev server).
echo   Buka di browser: %APP_URL%
echo   Stop semua proses: tekan Ctrl + C
echo.

REM pail butuh ekstensi pcntl, tidak tersedia di Windows.
REM Jadi log diikuti dengan Get-Content (mode tunggu) dari file laravel.log.
REM "npm run build -- --watch" = vite build mode pantau: bangun ulang aset tiap
REM file view/CSS/JS berubah, tapi tanpa nyalakan server dev vite.
REM Pemisah "--" penting supaya --watch diteruskan ke vite, bukan ditelan npm.
if not exist "storage\logs\laravel.log" type nul > "storage\logs\laravel.log"
npx concurrently -c "#93c5fd,#c4b5fd,#fb7185,#86efac" --names=server,queue,logs,assets "php artisan serve --host=%HOST% --port=%PORT%" "php artisan queue:listen --tries=1" "powershell -NoProfile -ExecutionPolicy Bypass -Command Get-Content 'storage/logs/laravel.log' -Wait -Tail 40" "npm run build -- --watch"

echo.
echo Semua proses berhenti.
pause
endlocal
