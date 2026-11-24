<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSampah;
use App\Models\Nasabah;
use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $startOfMonth = now()->startOfMonth();

        $stats = [
            'totalNasabah' => Nasabah::count(),
            'totalSetoranBulanIni' => TransaksiSetor::where('tanggal', '>=', $startOfMonth)->sum('total_nilai'),
            'totalVolumeKgBulanIni' => TransaksiSetor::where('tanggal', '>=', $startOfMonth)->sum('berat_kg'),
            'totalSaldoKeluarBulanIni' => TransaksiTarik::where('status', 'disetujui')
                ->where('tanggal', '>=', $startOfMonth)
                ->sum('jumlah'),
        ];

        $months = collect(range(0, 7))->map(fn ($i) => now()->subMonths(7 - $i)->startOfMonth());

        $setorPerBulan = TransaksiSetor::query()
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(total_nilai) as total")
            ->where('tanggal', '>=', $months->first())
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $tarikPerBulan = TransaksiTarik::query()
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m') as bulan, SUM(jumlah) as total")
            ->where('status', 'disetujui')
            ->where('tanggal', '>=', $months->first())
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $revenue = [
            'categories' => $months->map(fn (Carbon $m) => $m->translatedFormat('M'))->values(),
            'setor' => $months->map(fn (Carbon $m) => (float) ($setorPerBulan[$m->format('Y-m')] ?? 0))->values(),
            'tarik' => $months->map(fn (Carbon $m) => (float) ($tarikPerBulan[$m->format('Y-m')] ?? 0))->values(),
        ];

        $kategoriDistribusi = TransaksiSetor::query()
            ->join('item_sampah', 'item_sampah.id', '=', 'transaksi_setor.item_id')
            ->join('kategori_sampah', 'kategori_sampah.id', '=', 'item_sampah.kategori_id')
            ->selectRaw('kategori_sampah.nama_kategori as nama, SUM(transaksi_setor.berat_kg) as total_berat')
            ->groupBy('kategori_sampah.nama_kategori')
            ->orderByDesc('total_berat')
            ->limit(5)
            ->get();

        $days = collect(range(0, 29))->map(fn ($i) => now()->subDays(29 - $i)->startOfDay());

        $setorHarian = TransaksiSetor::query()
            ->selectRaw('tanggal, SUM(total_nilai) as total')
            ->where('tanggal', '>=', $days->first())
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $tarikHarian = TransaksiTarik::query()
            ->selectRaw('tanggal, SUM(jumlah) as total')
            ->where('status', 'disetujui')
            ->where('tanggal', '>=', $days->first())
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $sparklines = [
            'setor' => $days->map(fn (Carbon $d) => (float) ($setorHarian[$d->format('Y-m-d')] ?? 0))->values(),
            'tarik' => $days->map(fn (Carbon $d) => (float) ($tarikHarian[$d->format('Y-m-d')] ?? 0))->values(),
        ];

        $chartData = [
            'revenue' => $revenue,
            'kategoriDistribusi' => [
                'labels' => $kategoriDistribusi->pluck('nama')->values(),
                'series' => $kategoriDistribusi->pluck('total_berat')->map(fn ($v) => (float) $v)->values(),
            ],
            'sparklines' => $sparklines,
        ];

        $recentSetoran = TransaksiSetor::with(['nasabah.user', 'item'])
            ->latest('tanggal')->latest('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentSetoran'));
    }
}
