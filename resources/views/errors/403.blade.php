<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 - Akses Ditolak</title>
        <style>
            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f3f4f6;
                color: #1f2937;
                font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
                padding: 16px;
            }
            .kartu {
                background: #ffffff;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                max-width: 28rem;
                padding: 32px;
                text-align: center;
            }
            h1 { font-size: 1.35rem; margin: 0 0 12px; }
            p { font-size: 0.9rem; line-height: 1.6; color: #4b5563; margin: 0 0 8px; }
            a {
                display: inline-block;
                margin-top: 20px;
                background: #4f46e5;
                color: #ffffff;
                padding: 10px 16px;
                border-radius: 8px;
                font-size: 0.9rem;
                text-decoration: none;
            }
            a:hover { background: #4338ca; }
        </style>
    </head>
    <body>
        <div class="kartu">
            <h1>Akses Ditolak</h1>
            <p>Halaman ini tidak boleh dibuka oleh akun Anda.</p>
            <p>Silakan kembali ke dashboard yang sesuai dengan peran Anda.</p>
            <a href="{{ url('/dashboard') }}">Kembali ke Dashboard</a>
        </div>
    </body>
</html>
