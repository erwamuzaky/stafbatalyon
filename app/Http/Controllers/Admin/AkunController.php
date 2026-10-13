<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AkunController extends Controller
{
    /**
     * Daftar semua akun, dengan filter status dan pencarian nama/email.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $q = $request->query('q');

        $users = User::with('stafProfile.bidang')
            ->when(
                in_array($status, ['pending', 'approved', 'rejected'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->when(
                is_string($q) && $q !== '',
                fn ($query) => $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%');
                })
            )
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.akun.index', [
            'users' => $users,
            'status' => $status,
            'q' => $q,
        ]);
    }

    /**
     * Setujui akun supaya bisa masuk.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['status' => 'approved']);

        return back()->with('success', 'Akun '.$user->name.' sudah disetujui dan sekarang bisa masuk.');
    }

    /**
     * Tolak pendaftaran akun.
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menolak akun Anda sendiri.');
        }

        $user->update(['status' => 'rejected']);

        return back()->with('success', 'Pendaftaran akun '.$user->name.' sudah ditolak.');
    }

    /**
     * Nonaktifkan akun: status dikembalikan ke non-approved.
     */
    public function deactivate(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $user->update(['status' => 'pending']);

        return back()->with('success', 'Akun '.$user->name.' sudah dinonaktifkan. Statusnya kembali menjadi menunggu persetujuan.');
    }

    /**
     * Buat kata sandi acak baru. Kata sandi hanya ditampilkan sekali ke admin.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $plain = Str::password(10);

        $user->update(['password' => Hash::make($plain)]);

        return back()
            ->with('password_baru', [
                'nama' => $user->name,
                'email' => $user->email,
                'password' => $plain,
            ])
            ->with('success', 'Kata sandi akun '.$user->name.' sudah direset.');
    }

    /**
     * Hapus akun.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        return back()->with('success', 'Akun '.$nama.' sudah dihapus.');
    }
}
