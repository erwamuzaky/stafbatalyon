<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Pastikan user punya salah satu role yang diminta.
     * Contoh pemakaian di route: middleware('role:admin') atau middleware('role:admin,staf').
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak punya hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
