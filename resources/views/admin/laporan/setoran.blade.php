@extends('layouts.admin')

@section('title', 'Laporan Setoran')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Laporan Setoran</h1>
        <p class="page-subtitle">Total sampah masuk per periode.</p>
      </div>
    </div>

    <div class="table-card-custom mb-4">
      <div class="table-header-control">
        <form method="GET" class="d-flex flex-wrap gap-2 w-100 align-items-center">
          <input type="date" name="from" class="form-control-custom" style="max-width: 170px;" value="{{ request('from') }}">
          <input type="date" name="to" class="form-control-custom" style="max-width: 170px;" value="{{ request('to') }}">
          <select name="groupBy" class="form-select-custom" style="max-width: 160px;">
            <option value="harian" {{ $groupBy === 'harian' ? 'selected' : '' }}>Harian</option>
            <option value="bulanan" {{ $groupBy === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
          </select>
          <button type="submit" class="btn-table-action">Terapkan Filter</button>

          <div class="ms-auto d-flex gap-2">
            <a href="{{ route('admin.laporan.setoran.exportCsv', request()->query()) }}" class="btn-table-action">
              <i class="bi bi-file-earmark-arrow-down"></i> CSV
            </a>
            <a href="{{ route('admin.laporan.setoran.exportPdf', request()->query()) }}" class="btn-table-action">
              <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
          </div>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Periode</th>
              <th>Jumlah Transaksi</th>
              <th>Total Berat (kg)</th>
              <th>Total Nilai</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($rows as $row)
              <tr>
                <td>{{ $row->periode }}</td>
                <td>{{ $row->jumlah_transaksi }}</td>
                <td>{{ number_format($row->total_berat, 2) }}</td>
                <td class="table-amount">Rp{{ number_format($row->total_nilai, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted-green py-4">Tidak ada data pada rentang ini.</td>
              </tr>
            @endforelse
          </tbody>
          @if ($rows->isNotEmpty())
            <tfoot>
              <tr>
                <th>Subtotal Halaman Ini</th>
                <th>{{ $rows->sum('jumlah_transaksi') }}</th>
                <th>{{ number_format($rows->sum('total_berat'), 2) }}</th>
                <th>Rp{{ number_format($rows->sum('total_nilai'), 0, ',', '.') }}</th>
              </tr>
              <tr>
                <th>Total Keseluruhan</th>
                <th>{{ $totals->jumlah_transaksi }}</th>
                <th>{{ number_format($totals->total_berat, 2) }}</th>
                <th>Rp{{ number_format($totals->total_nilai, 0, ',', '.') }}</th>
              </tr>
            </tfoot>
          @endif
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} dari {{ $rows->total() }} periode
        </span>
        {{ $rows->links() }}
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Tren Volume per Kategori</h2>
      </div>
      <div id="kategoriTrendChart"></div>
    </div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const el = document.querySelector('#kategoriTrendChart');
    if (!el) return;

    const chart = new ApexCharts(el, {
      series: [{
        name: 'Total Berat (kg)',
        data: @json($kategoriTrend->pluck('total_berat'))
      }],
      chart: { type: 'bar', height: 280, toolbar: { show: false } },
      colors: ['#B4F105'],
      plotOptions: { bar: { borderRadius: 4, horizontal: true } },
      xaxis: { categories: @json($kategoriTrend->pluck('nama')) }
    });
    chart.render();
  });
</script>
@endpush
