<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStafRequest;
use App\Http\Requests\Admin\UpdateStafRequest;
use App\Models\Bidang;
use App\Models\StafProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StafController extends Controller
{
    /**
     * Daftar staf dengan pencarian dan filter bidang serta kelas.
     */
    public function index(Request $request): View
    {
        $q = $request->query('q');
        $bidangId = $request->query('bidang_id');
        $kelas = $request->query('kelas');

        $users = User::with('stafProfile.bidang')
            ->where('role', 'staf')
            ->when(
                is_string($q) && $q !== '',
                fn ($query) => $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%');
                })
            )
            ->when(
                is_string($bidangId) && $bidangId !== '',
                fn ($query) => $query->whereHas('stafProfile', function ($sub) use ($bidangId) {
                    $sub->where('bidang_id', $bidangId);
                })
            )
            ->when(
                is_string($kelas) && $kelas !== '',
                fn ($query) => $query->whereHas('stafProfile', function ($sub) use ($kelas) {
                    $sub->where('kelas', 'like', '%'.$kelas.'%');
                })
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.staf.index', [
            'users' => $users,
            'bidangs' => Bidang::orderBy('nama')->get(),
            'kelasList' => StafProfile::query()
                ->whereNotNull('kelas')
                ->where('kelas', '!=', '')
                ->distinct()
                ->orderBy('kelas')
                ->pluck('kelas'),
            'q' => $q,
            'bidangId' => $bidangId,
            'kelas' => $kelas,
        ]);
    }

    /**
     * Form tambah staf manual.
     */
    public function create(): View
    {
        return view('admin.staf.create', [
            'bidangs' => Bidang::orderBy('nama')->get(),
        ]);
    }

    /**
     * Simpan staf baru yang ditambahkan admin. Langsung berstatus disetujui.
     */
    public function store(StoreStafRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staf',
                'status' => 'approved',
            ]);

            $user->stafProfile()->create([
                'bidang_id' => $validated['bidang_id'],
                'kelas' => $validated['kelas'],
                'jabatan' => $validated['jabatan'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'is_inti' => $request->boolean('is_inti'),
                'foto' => $request->hasFile('foto') ? $request->file('foto')->store('staf', 'public') : null,
            ]);

            return $user;
        });

        return redirect()->route('admin.staf.index')
            ->with('success', 'Staf '.$user->name.' sudah ditambahkan dan langsung berstatus disetujui.');
    }

    /**
     * Form edit data staf (bidang, jabatan, nomor HP, status inti).
     */
    public function edit(User $user): View
    {
        if ($user->role !== 'staf') {
            abort(404);
        }

        return view('admin.staf.edit', [
            'user' => $user,
            'profile' => $user->stafProfile,
            'bidangs' => Bidang::orderBy('nama')->get(),
        ]);
    }

    /**
     * Simpan perubahan data staf.
     */
    public function update(UpdateStafRequest $request, User $user): RedirectResponse
    {
        if ($user->role !== 'staf') {
            abort(404);
        }

        $validated = $request->validated();

        $profile = $user->stafProfile;

        if ($request->hasFile('foto')) {
            $lama = $profile?->foto;
            if ($lama) {
                Storage::disk('public')->delete($lama);
            }
            $fotoPath = $request->file('foto')->store('staf', 'public');
            $user->stafProfile()->updateOrCreate([], [
                'bidang_id' => $validated['bidang_id'],
                'kelas' => $validated['kelas'],
                'jabatan' => $validated['jabatan'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'is_inti' => $request->boolean('is_inti'),
                'foto' => $fotoPath,
            ]);
        } elseif ($request->boolean('hapus_foto')) {
            $lama = $profile?->foto;
            if ($lama) {
                Storage::disk('public')->delete($lama);
            }
            $user->stafProfile()->updateOrCreate([], [
                'bidang_id' => $validated['bidang_id'],
                'kelas' => $validated['kelas'],
                'jabatan' => $validated['jabatan'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'is_inti' => $request->boolean('is_inti'),
                'foto' => null,
            ]);
        } else {
            $user->stafProfile()->updateOrCreate([], [
                'bidang_id' => $validated['bidang_id'],
                'kelas' => $validated['kelas'],
                'jabatan' => $validated['jabatan'] ?? null,
                'no_hp' => $validated['no_hp'] ?? null,
                'is_inti' => $request->boolean('is_inti'),
                'foto' => $profile?->foto,
            ]);
        }

        return redirect()->route('admin.staf.index')
            ->with('success', 'Data staf '.$user->name.' sudah diperbarui.');
    }

    /**
     * Hapus staf beserta profilnya.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Akun admin tidak bisa dihapus dari halaman Data Staf.');
        }

        $nama = $user->name;
        $user->delete();

        return back()->with('success', 'Staf '.$nama.' sudah dihapus.');
    }
}
