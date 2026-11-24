<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\PenarikanSudahDiprosesException;
use App\Exceptions\SaldoTidakCukupException;
use App\Http\Controllers\Controller;
use App\Models\TransaksiTarik;
use App\Services\PenarikanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiTarikController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $penarikan = TransaksiTarik::query()
            ->with(['nasabah.user', 'petugas'])
            ->when($status !== 'semua', fn ($q) => $q->where('status', $status))
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.penarikan.index', compact('penarikan', 'status'));
    }

    public function approve(TransaksiTarik $penarikan, PenarikanService $service): RedirectResponse
    {
        try {
            $service->approve($penarikan, auth()->user());
        } catch (SaldoTidakCukupException|PenarikanSudahDiprosesException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Penarikan berhasil disetujui.');
    }

    public function reject(TransaksiTarik $penarikan, PenarikanService $service): RedirectResponse
    {
        try {
            $service->reject($penarikan, auth()->user());
        } catch (PenarikanSudahDiprosesException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Penarikan berhasil ditolak.');
    }
}
