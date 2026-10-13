<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProposalDasarRequest;
use App\Http\Requests\Admin\StoreProposalRequest;
use App\Models\Event;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProposalController extends Controller
{
    /**
     * Unggah proposal baru untuk sebuah kegiatan.
     */
    public function store(StoreProposalRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();

        $event->proposals()->create([
            'judul' => $validated['judul'],
            'catatan' => $validated['catatan'] ?? null,
            'file_path' => $request->file('file')->store('proposals', 'public'),
            'template_dari_id' => null,
        ]);

        return redirect()->route('admin.event.show', $event)
            ->with('success', 'Proposal '.$validated['judul'].' sudah diunggah.');
    }

    /**
     * Form pilih kegiatan tujuan saat memakai proposal lama sebagai dasar.
     */
    public function createDari(Proposal $proposal): View
    {
        return view('admin.proposal.dasar', [
            'proposal' => $proposal,
            'events' => Event::orderByDesc('tahun')->orderBy('nama')->get(),
        ]);
    }

    /**
     * Buat proposal baru memakai proposal lama sebagai dasar.
     * Berkas asli disalin supaya kedua proposal punya berkas sendiri.
     */
    public function storeDari(StoreProposalDasarRequest $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validated();
        $tujuan = Event::findOrFail($validated['event_id']);

        $tujuan->proposals()->create([
            'judul' => $validated['judul'],
            'catatan' => $validated['catatan'] ?? null,
            'file_path' => $this->salinBerkas($proposal->file_path),
            'template_dari_id' => $proposal->id,
        ]);

        return redirect()->route('admin.event.show', $tujuan)
            ->with('success', 'Proposal baru sudah dibuat untuk kegiatan '.$tujuan->nama.' berdasarkan proposal '.$proposal->judul.'.');
    }

    /**
     * Hapus proposal. Berkas fisik ikut terhapus lewat model Proposal.
     */
    public function destroy(Proposal $proposal): RedirectResponse
    {
        $eventId = $proposal->event_id;
        $judul = $proposal->judul;

        $proposal->delete();

        return redirect()->route('admin.event.show', $eventId)
            ->with('success', 'Proposal '.$judul.' sudah dihapus.');
    }

    /**
     * Salin berkas proposal ke nama baru yang unik.
     */
    private function salinBerkas(string $asal): string
    {
        $ekstensi = pathinfo($asal, PATHINFO_EXTENSION);
        $tujuan = 'proposals/'.Str::random(40).($ekstensi !== '' ? '.'.$ekstensi : '');

        Storage::disk('public')->copy($asal, $tujuan);

        return $tujuan;
    }
}
