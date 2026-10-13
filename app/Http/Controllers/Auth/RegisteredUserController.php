<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman daftar akun staf.
     */
    public function create(): View
    {
        return view('auth.register', [
            'bidangs' => Bidang::orderBy('nama')->get(),
        ]);
    }

    /**
     * Simpan pendaftaran staf baru.
     *
     * User dibuat dengan role staf dan status pending, lalu langsung dibuatkan
     * profilnya. Setelah daftar user TIDAK otomatis login, tapi diarahkan ke
     * halaman "menunggu persetujuan admin".
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'bidang_id' => ['required', 'exists:bidangs,id'],
            'no_hp' => ['required', 'string', 'max:20'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staf',
                'status' => 'pending',
            ]);

            $user->stafProfile()->create([
                'bidang_id' => $validated['bidang_id'],
                'no_hp' => $validated['no_hp'],
                'is_inti' => false,
            ]);

            return $user;
        });

        event(new Registered($user));

        return redirect()->route('registration.pending');
    }
}
