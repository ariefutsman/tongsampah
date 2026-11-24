<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    private function filteredSetoranQuery(Request $request)
    {
        return TransaksiSetor::query()
            ->with(['nasabah.user', 'item.kategori'])
            ->when($request->filled('from'), fn ($q) => $q->whereDate('tanggal', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('tanggal', '<=', $request->date('to')))
            ->orderBy('tanggal');
    }

    private function filteredTarikQuery(Request $request)
    {
        return TransaksiTarik::query()
            ->with(['nasabah.user'])
            ->where('status', 'disetujui')
            ->when($request->filled('from'), fn ($q) => $q->whereDate('tanggal', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('tanggal', '<=', $request->date('to')))
            ->orderBy('tanggal');
    }

    public function setoran(Request $request): View
    {
        $groupBy = $request->get('groupBy', 'harian');
        $format = $groupBy === 'bulanan' ? '%Y-%m' : '%Y-%m-%d';

        $rows = $this->filteredSetoranQuery($request)
            ->reorder()
            ->selectRaw("DATE_FORMAT(tanggal, '{$format}') as periode, SUM(berat_kg) as total_berat, SUM(total_nilai) as total_nilai, COUNT(*) as jumlah_transaksi")
            ->groupBy('periode')
            ->orderBy('periode')
            ->paginate(15)
            ->withQueryString();

        $totals = $this->filteredSetoranQuery($request)
            ->reorder()
            ->selectRaw('COUNT(*) as jumlah_transaksi, SUM(berat_kg) as total_berat, SUM(total_nilai) as total_nilai')
            ->first();

        $kategoriTrend = $this->filteredSetoranQuery($request)
            ->reorder()
            ->join('item_sampah', 'item_sampah.id', '=', 'transaksi_setor.item_id')
            ->join('kategori_sampah', 'kategori_sampah.id', '=', 'item_sampah.kategori_id')
            ->selectRaw('kategori_sampah.nama_kategori as nama, SUM(transaksi_setor.berat_kg) as total_berat')
            ->groupBy('kategori_sampah.nama_kategori')
            ->orderByDesc('total_berat')
            ->get();

        return view('admin.laporan.setoran', compact('rows', 'totals', 'kategoriTrend', 'groupBy'));
    }

    public function setoranExportCsv(Request $request): StreamedResponse
    {
        $query = $this->filteredSetoranQuery($request);

        return Response::streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Nasabah', 'Item Sampah', 'Berat (kg)', 'Total Nilai']);

            $query->chunk(500, function ($chunk) use ($handle) {
                foreach ($chunk as $row) {
                    fputcsv($handle, [
                        $row->tanggal->format('Y-m-d'),
                        $row->nasabah->user->name,
                        $row->item->nama_item,
                        $row->berat_kg,
                        $row->total_nilai,
                    ]);
                }
            });

            fclose($handle);
        }, 'laporan-setoran-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function setoranExportPdf(Request $request)
    {
        $rows = $this->filteredSetoranQuery($request)->get();
        $from = $request->get('from');
        $to = $request->get('to');

        $pdf = Pdf::loadView('admin.laporan.pdf.setoran', compact('rows', 'from', 'to'));

        return $pdf->download('laporan-setoran-'.now()->format('Ymd').'.pdf');
    }

    public function penarikan(Request $request): View
    {
        $groupBy = $request->get('groupBy', 'harian');
        $format = $groupBy === 'bulanan' ? '%Y-%m' : '%Y-%m-%d';

        $rows = $this->filteredTarikQuery($request)
            ->reorder()
            ->selectRaw("DATE_FORMAT(tanggal, '{$format}') as periode, SUM(jumlah) as total_jumlah, COUNT(*) as jumlah_transaksi")
            ->groupBy('periode')
            ->orderBy('periode')
            ->paginate(15)
            ->withQueryString();

        $totals = $this->filteredTarikQuery($request)
            ->reorder()
            ->selectRaw('COUNT(*) as jumlah_transaksi, SUM(jumlah) as total_jumlah')
            ->first();

        return view('admin.laporan.penarikan', compact('rows', 'totals', 'groupBy'));
    }

    public function penarikanExportCsv(Request $request): StreamedResponse
    {
        $query = $this->filteredTarikQuery($request);

        return Response::streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Nasabah', 'Jumlah']);

            $query->chunk(500, function ($chunk) use ($handle) {
                foreach ($chunk as $row) {
                    fputcsv($handle, [
                        $row->tanggal->format('Y-m-d'),
                        $row->nasabah->user->name,
                        $row->jumlah,
                    ]);
                }
            });

            fclose($handle);
        }, 'laporan-penarikan-'.now()->format('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function penarikanExportPdf(Request $request)
    {
        $rows = $this->filteredTarikQuery($request)->get();
        $from = $request->get('from');
        $to = $request->get('to');

        $pdf = Pdf::loadView('admin.laporan.pdf.penarikan', compact('rows', 'from', 'to'));

        return $pdf->download('laporan-penarikan-'.now()->format('Ymd').'.pdf');
    }
}
