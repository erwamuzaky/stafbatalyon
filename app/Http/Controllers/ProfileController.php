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

        // Nomor HP staf disimpan pada profil staf, bukan pada tabel user.
        $profil = $user->stafProfile;

        if ($profil !== null && $request->has('no_hp')) {
            $profil->update(['no_hp' => $request->input('no_hp')]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
