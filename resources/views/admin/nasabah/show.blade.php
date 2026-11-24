@extends('layouts.admin')

@section('title', 'Profil Nasabah')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">{{ $nasabah->user->name }}</h1>
        <p class="page-subtitle">{{ $nasabah->user->no_hp ?? '-' }} · {{ $nasabah->alamat ?? 'Alamat belum diisi' }}</p>
      </div>
      <a href="{{ route('admin.setoran.create', ['nasabah_id' => $nasabah->id]) }}" class="btn-date-picker">
        <i class="bi bi-box-arrow-in-down"></i>
        <span>Input Setoran</span>
      </a>
    </div>

    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card card-stat">
          <span class="stat-label">Saldo Saat Ini</span>
          <div class="stat-value">Rp{{ number_format($nasabah->saldo, 0, ',', '.') }}</div>
        </div>
      </div>
    </div>

    <div class="table-card-custom mb-4">
      <div class="table-header-control">
        <h2 class="card-title mb-0">Riwayat Setoran</h2>
      </div>
      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Item Sampah</th>
              <th>Berat (kg)</th>
              <th>Nilai</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($setoran as $item)
              <tr>
                <td>{{ $item->tanggal->format('d M Y') }}</td>
                <td>{{ $item->item->nama_item }}</td>
                <td>{{ $item->berat_kg }}</td>
                <td class="table-amount">Rp{{ number_format($item->total_nilai, 0, ',', '.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted-green py-4">Belum ada riwayat setoran.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="table-footer-control">
        {{ $setoran->links() }}
      </div>
    </div>

    <div class="table-card-custom">
      <div class="table-header-control">
        <h2 class="card-title mb-0">Riwayat Penarikan</h2>
      </div>
      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Jumlah</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($tarik as $item)
              <tr>
                <td>{{ $item->tanggal->format('d M Y') }}</td>
                <td class="table-amount">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>
                  <span class="badge-table {{ $item->status === 'disetujui' ? 'success' : ($item->status === 'ditolak' ? 'failed' : 'pending') }}">
                    {{ ucfirst($item->status) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center text-muted-green py-4">Belum ada riwayat penarikan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="table-footer-control">
        {{ $tarik->links() }}
      </div>
    </div>

@endsection
