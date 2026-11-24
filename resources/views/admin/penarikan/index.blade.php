@extends('layouts.admin')

@section('title', 'Verifikasi Penarikan')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Verifikasi Penarikan</h1>
        <p class="page-subtitle">Setujui atau tolak pengajuan penarikan saldo nasabah.</p>
      </div>
    </div>

    <div class="table-card-custom">
      <div class="table-header-control">
        <div class="table-filter-group">
          @foreach (['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak', 'semua' => 'Semua'] as $key => $label)
            <a href="{{ route('admin.penarikan.index', ['status' => $key]) }}"
              class="btn-table-action {{ $status === $key ? 'active' : '' }}">{{ $label }}</a>
          @endforeach
        </div>
      </div>

      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Nasabah</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th>Diproses Oleh</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($penarikan as $item)
              <tr>
                <td>{{ $item->tanggal->format('d M Y') }}</td>
                <td>{{ $item->nasabah->user->name }}</td>
                <td class="table-amount">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>
                  <span class="badge-table {{ $item->status === 'disetujui' ? 'success' : ($item->status === 'ditolak' ? 'failed' : 'pending') }}">
                    {{ ucfirst($item->status) }}
                  </span>
                </td>
                <td>{{ $item->petugas->name ?? '-' }}</td>
                <td>
                  @if ($item->status === 'pending')
                    <div class="d-flex justify-content-center gap-1">
                      <form action="{{ route('admin.penarikan.approve', $item) }}" method="POST"
                        onsubmit="return confirm('Setujui penarikan Rp{{ number_format($item->jumlah, 0, ',', '.') }} untuk {{ $item->nasabah->user->name }}?');">
                        @csrf
                        <button type="submit" class="table-btn-action" title="Setujui"><i class="bi bi-check2-circle"></i></button>
                      </form>
                      <form action="{{ route('admin.penarikan.reject', $item) }}" method="POST"
                        onsubmit="return confirm('Tolak penarikan ini?');">
                        @csrf
                        <button type="submit" class="table-btn-action delete" title="Tolak"><i class="bi bi-x-circle"></i></button>
                      </form>
                    </div>
                  @else
                    <span class="text-muted text-center d-block">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted-green py-4">Tidak ada pengajuan penarikan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $penarikan->firstItem() ?? 0 }} - {{ $penarikan->lastItem() ?? 0 }} dari {{ $penarikan->total() }} pengajuan
        </span>
        {{ $penarikan->links() }}
      </div>
    </div>

@endsection
