<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArsipController extends Controller
{
    /**
     * Arsip proposal dari semua kegiatan, bisa dicari dan disaring per tahun.
     */
    public function index(Request $request): View
    {
        $q = $request->query('q');
        $tahun = $request->query('tahun');

        $proposals = Proposal::with(['event', 'templateDari.event'])
            ->when(
                is_string($q) && $q !== '',
                fn ($query) => $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', '%'.$q.'%')
                        ->orWhereHas('event', fn ($event) => $event->where('nama', 'like', '%'.$q.'%'));
                })
            )
            ->when(
                is_string($tahun) && $tahun !== '',
                fn ($query) => $query->whereHas('event', fn ($event) => $event->where('tahun', $tahun))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.arsip.index', [
            'proposals' => $proposals,
            'tahunPilihan' => Event::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
            'q' => $q,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Unduh berkas proposal.
     */
    public function unduh(Proposal $proposal): StreamedResponse|RedirectResponse
    {
        if (! Storage::disk('public')->exists($proposal->file_path)) {
            return redirect()->route('admin.arsip.index')
                ->with('error', 'Berkas proposal '.$proposal->judul.' tidak ditemukan di penyimpanan.');
        }

        $ekstensi = pathinfo($proposal->file_path, PATHINFO_EXTENSION);
        $namaUnduhan = $proposal->judul.($ekstensi !== '' ? '.'.$ekstensi : '');

        return Storage::disk('public')->download($proposal->file_path, $namaUnduhan);
    }
}
