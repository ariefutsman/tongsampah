@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <!-- START: Dashboard Header Banner -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Ringkasan operasional bank sampah.</p>
      </div>
    </div>
    <!-- END: Dashboard Header Banner -->

    <!-- START: Main Layout Grid -->
    <div class="row g-4">

      <!-- TOP AREA: Quick Info Stat Cards Row -->
      <div class="col-12">
        <div class="row g-4">
          <div class="col-md-3">
            <div class="card card-stat">
              <span class="stat-label">Total Nasabah</span>
              <div class="stat-value">{{ number_format($stats['totalNasabah']) }}</div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card card-stat d-flex flex-column justify-content-between">
              <div>
                <span class="stat-label">Setoran Bulan Ini</span>
                <div class="stat-value">Rp{{ number_format($stats['totalSetoranBulanIni'], 0, ',', '.') }}</div>
              </div>
              <div class="sparkline-container sparkline-card-footer">
                <div id="income-sparkline"></div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card card-stat">
              <span class="stat-label">Volume Sampah Bulan Ini</span>
              <div class="stat-value">{{ number_format($stats['totalVolumeKgBulanIni'], 1) }} kg</div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card card-stat d-flex flex-column justify-content-between">
              <div>
                <span class="stat-label">Saldo Keluar Bulan Ini</span>
                <div class="stat-value">Rp{{ number_format($stats['totalSaldoKeluarBulanIni'], 0, ',', '.') }}</div>
              </div>
              <div class="sparkline-container sparkline-card-footer">
                <div id="return-sparkline"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- END: TOP AREA -->

      <!-- LEFT AREA -->
      <div class="col-xl-9 col-lg-8">
        <div class="row g-4">
          <div class="col-12">
            <div class="card mb-0">
              <div class="card-header mb-2">
                <h2 class="card-title">Setoran vs Penarikan (8 Bulan Terakhir)</h2>
                <div class="d-flex gap-3 align-items-center">
                  <div class="chart-legend-item">
                    <span class="legend-dot bg-forest-medium"></span>
                    <span class="chart-legend-label">Setoran</span>
                  </div>
                  <div class="chart-legend-item">
                    <span class="legend-dot bg-lime-accent"></span>
                    <span class="chart-legend-label">Penarikan</span>
                  </div>
                </div>
              </div>
              <div id="revenue-chart"></div>
            </div>
          </div>

          <div class="col-12">
            <div class="card h-100">
              <div class="card-header">
                <h2 class="card-title">Setoran Terbaru</h2>
              </div>

              <div class="transaction-list">
                @forelse ($recentSetoran as $row)
                  <div class="transaction-item">
                    <div class="transaction-icon bg-forest-light text-lime">
                      <i class="bi bi-recycle"></i>
                    </div>
                    <div class="transaction-info">
                      <div class="transaction-name">{{ $row->nasabah->user->name }} — {{ $row->item->nama_item }}</div>
                      <div class="transaction-date">{{ $row->tanggal->translatedFormat('d M Y') }} • {{ $row->berat_kg }} kg</div>
                    </div>
                    <div class="transaction-amount text-success">+Rp{{ number_format($row->total_nilai, 0, ',', '.') }}</div>
                  </div>
                @empty
                  <p class="text-muted-green p-3 mb-0">Belum ada transaksi setoran.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT AREA -->
      <div class="col-xl-3 col-lg-4">
        <div class="right-panel-wrapper d-flex flex-column gap-4 h-100">
          <div class="card flex-grow-1 d-flex flex-column justify-content-between mb-0">
            <div class="card-header mb-1">
              <h2 class="card-title">Distribusi Kategori Sampah</h2>
            </div>

            <div id="views-chart"></div>

            @php
              $donutColors = ['#B4F105', '#051C12', '#F97316', '#22C55E', '#EF4444'];
            @endphp
            <div class="chart-legends-container">
              @foreach ($chartData['kategoriDistribusi']['labels'] as $index => $label)
                <div class="chart-legend-item">
                  <span class="legend-dot" style="background-color: {{ $donutColors[$index % count($donutColors)] }};"></span>
                  <span class="text-muted-green">{{ $label }}</span>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <!-- END: RIGHT AREA -->

    </div>
    <!-- END: Main Layout Grid -->

@endsection

@push('scripts')
<script>
  window.dashboardChartData = @json($chartData);
</script>
@endpush
