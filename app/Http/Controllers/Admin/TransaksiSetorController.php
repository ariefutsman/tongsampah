<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSampah;
use App\Models\Nasabah;
use App\Models\TransaksiSetor;
use App\Services\SetoranService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiSetorController extends Controller
{
    public function index(Request $request): View
    {
        $setoran = TransaksiSetor::query()
            ->with(['nasabah.user', 'item.kategori', 'petugas'])
            ->when($request->filled('nasabah_id'), fn ($q) => $q->where('nasabah_id', $request->integer('nasabah_id')))
            ->when($request->filled('item_id'), fn ($q) => $q->where('item_id', $request->integer('item_id')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('tanggal', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('tanggal', '<=', $request->date('to')))
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $kategoriList = KategoriSampah::with(['items' => fn ($q) => $q->orderBy('nama_item')])
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.setoran.index', compact('setoran', 'kategoriList'));
    }

    public function create(Request $request): View
    {
        $kategoriList = KategoriSampah::where('is_active', true)
            ->with(['items' => fn ($q) => $q->where('is_active', true)->orderBy('nama_item')])
            ->orderBy('nama_kategori')
            ->get();
        $nasabahTerpilih = null;

        if ($request->filled('nasabah_id')) {
            $nasabahTerpilih = Nasabah::with('user')->find($request->integer('nasabah_id'));
        }

        $nasabahList = Nasabah::with('user')->get()->sortBy(fn ($n) => $n->user->name);

        return view('admin.setoran.create', compact('kategoriList', 'nasabahTerpilih', 'nasabahList'));
    }

    public function store(Request $request, SetoranService $service): RedirectResponse
    {
        $data = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabah,id'],
            'item_id' => ['required', 'exists:item_sampah,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.01'],
            'tanggal' => ['nullable', 'date'],
        ]);

        $data['petugas_id'] = auth()->id();

        $setoran = $service->store($data);

        return redirect()->route('admin.setoran.index')
            ->with('success', 'Setoran berhasil dicatat. Total nilai: Rp'.number_format($setoran->total_nilai, 0, ',', '.'));
    }

    public function bukti(TransaksiSetor $setoran)
    {
        $setoran->load(['nasabah.user', 'item.kategori', 'petugas']);

        $pdf = Pdf::loadView('admin.setoran.bukti-pdf', compact('setoran'));

        return $pdf->stream('bukti-setoran-'.$setoran->id.'.pdf');
    }
}
