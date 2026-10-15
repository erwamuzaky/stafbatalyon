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
            'kelas' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staf',
                'status' => 'pending',
            ]);

            $user->stafProfile()->create([
                'bidang_id' => $validated['bidang_id'],
                'kelas' => $validated['kelas'],
                'jabatan' => $validated['jabatan'] ?? null,
                'no_hp' => $validated['no_hp'],
                'is_inti' => false,
                'foto' => $request->hasFile('foto') ? $request->file('foto')->store('staf', 'public') : null,
            ]);

            return $user;
        });

        event(new Registered($user));

        return redirect()->route('registration.pending');
    }
}
