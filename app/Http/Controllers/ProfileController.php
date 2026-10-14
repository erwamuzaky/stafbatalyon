<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()->load('stafProfile.bidang'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Kelas dan nomor HP staf disimpan pada profil staf, bukan pada tabel user.
        $profil = $user->stafProfile;

        if ($profil !== null) {
            $dataProfil = [];

            if ($request->has('kelas')) {
                $dataProfil['kelas'] = $request->input('kelas');
            }

            if ($request->has('no_hp')) {
                $dataProfil['no_hp'] = $request->input('no_hp');
            }

            if ($dataProfil !== []) {
                $profil->update($dataProfil);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
