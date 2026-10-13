<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Pilihan status kegiatan beserta labelnya.
     *
     * @var array<string, string>
     */
    public const STATUS = [
        'rencana' => 'Rencana',
        'berjalan' => 'Berjalan',
        'selesai' => 'Selesai',
    ];

    /**
     * Daftar kegiatan dengan pencarian dan filter tahun serta status.
     */
    public function index(Request $request): View
    {
        $q = $request->query('q');
        $tahun = $request->query('tahun');
        $status = $request->query('status');

        $events = Event::withCount('proposals')
            ->when(
                is_string($q) && $q !== '',
                fn ($query) => $query->where('nama', 'like', '%'.$q.'%')
            )
            ->when(
                is_string($tahun) && $tahun !== '',
                fn ($query) => $query->where('tahun', $tahun)
            )
            ->when(
                is_string($status) && $status !== '',
                fn ($query) => $query->where('status', $status)
            )
            ->orderByDesc('tahun')
            ->orderBy('tanggal_mulai')
            ->paginate(15)
            ->withQueryString();

        return view('admin.event.index', [
            'events' => $events,
            'tahunPilihan' => Event::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
            'statusPilihan' => self::STATUS,
            'q' => $q,
            'tahun' => $tahun,
            'status' => $status,
        ]);
    }

    /**
     * Form tambah kegiatan.
     */
    public function create(): View
    {
        return view('admin.event.create', [
            'statusPilihan' => self::STATUS,
        ]);
    }

    /**
     * Simpan kegiatan baru.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = Event::create($request->validated());

        return redirect()->route('admin.event.index')
            ->with('success', 'Kegiatan '.$event->nama.' sudah ditambahkan.');
    }

    /**
     * Detail kegiatan beserta proposalnya.
     */
    public function show(Event $event): View
    {
        return view('admin.event.show', [
            'event' => $event,
            'proposals' => $event->proposals()
                ->with('templateDari.event')
                ->withCount('turunan')
                ->latest()
                ->get(),
            'statusPilihan' => self::STATUS,
        ]);
    }

    /**
     * Form ubah kegiatan.
     */
    public function edit(Event $event): View
    {
        return view('admin.event.edit', [
            'event' => $event,
            'statusPilihan' => self::STATUS,
        ]);
    }

    /**
     * Simpan perubahan kegiatan.
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        return redirect()->route('admin.event.show', $event)
            ->with('success', 'Kegiatan '.$event->nama.' sudah diperbarui.');
    }

    /**
     * Hapus kegiatan beserta seluruh proposal dan berkasnya.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $nama = $event->nama;

        foreach ($event->proposals as $proposal) {
            // Berkas fisik ikut terhapus lewat model Proposal.
            $proposal->delete();
        }

        $event->delete();

        return redirect()->route('admin.event.index')
            ->with('success', 'Kegiatan '.$nama.' beserta proposalnya sudah dihapus.');
    }
}
