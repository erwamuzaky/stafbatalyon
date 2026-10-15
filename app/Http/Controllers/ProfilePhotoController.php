<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilePhotoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    /**
     * Pasang atau ganti foto profil staf yang sedang masuk.
     *
     * Foto disimpan di disk "public" folder "staf" (sama seperti unggahan
     * admin). Berkas lama dihapus dulu supaya tidak menumpuk sampah.
     */
    public function update(UpdateProfilePhotoRequest $request): RedirectResponse
    {
        $profil = $request->user()->stafProfile;

        if ($profil->foto) {
            Storage::disk('public')->delete($profil->foto);
        }

        $profil->update([
            'foto' => $request->file('foto')->store('staf', 'public'),
        ]);

        return back()->with('success', 'Foto profil sudah diperbarui.');
    }

    /**
     * Hapus foto profil staf yang sedang masuk.
     */
    public function destroy(UpdateProfilePhotoRequest $request): RedirectResponse
    {
        $profil = $request->user()->stafProfile;

        if ($profil->foto) {
            Storage::disk('public')->delete($profil->foto);
        }

        $profil->update(['foto' => null]);

        return back()->with('success', 'Foto profil sudah dihapus.');
    }
}
