<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApproved
{
    /**
     * Hanya akun yang sudah disetujui admin yang boleh memakai halaman ini.
     * Akun pending atau rejected langsung dikeluarkan lalu diarahkan ke halaman masuk.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status !== 'approved') {
            $message = $user->status === 'rejected'
                ? 'Pendaftaran akun Anda ditolak admin. Silakan hubungi admin sekolah.'
                : 'Akun Anda masih menunggu persetujuan admin. Silakan masuk lagi setelah akun disetujui.';

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}
