@extends('layouts.admin')

@section('title', 'Input Setoran')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Setoran Sampah</h1>
        <p class="page-subtitle">Daftar transaksi setoran sampah dari nasabah.</p>
      </div>
      <a href="{{ route('admin.setoran.create') }}" class="btn-date-picker">
        <i class="bi bi-plus-lg"></i>
        <span>Input Setoran</span>
      </a>
    </div>

    <div class="table-card-custom">
      <div class="table-header-control">
        <form method="GET" class="d-flex flex-wrap gap-2 w-100">
          <select name="item_id" class="form-select-custom" style="max-width: 220px;" onchange="this.form.submit()">
            <option value="">Semua Item</option>
            @foreach ($kategoriList as $kategori)
              @if ($kategori->items->isNotEmpty())
                <optgroup label="{{ $kategori->nama_kategori }}">
                  @foreach ($kategori->items as $item)
                    <option value="{{ $item->id }}" {{ (string) request('item_id') === (string) $item->id ? 'selected' : '' }}>
                      {{ $item->nama_item }}
                    </option>
                  @endforeach
                </optgroup>
              @endif
            @endforeach
          </select>
          <input type="date" name="from" class="form-control-custom" style="max-width: 160px;" value="{{ request('from') }}">
          <input type="date" name="to" class="form-control-custom" style="max-width: 160px;" value="{{ request('to') }}">
          <button type="submit" class="btn-table-action">Filter</button>
        </form>
      </div>

      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Nasabah</th>
              <th>Item Sampah</th>
              <th>Berat (kg)</th>
              <th>Nilai</th>
              <th>Petugas</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($setoran as $row)
              <tr>
                <td>{{ $row->tanggal->format('d M Y') }}</td>
                <td>{{ $row->nasabah->user->name }}</td>
                <td>{{ $row->item->nama_item }}</td>
                <td>{{ $row->berat_kg }}</td>
                <td class="table-amount text-success">+Rp{{ number_format($row->total_nilai, 0, ',', '.') }}</td>
                <td>{{ $row->petugas->name }}</td>
                <td class="text-center">
                  <a href="{{ route('admin.setoran.bukti', $row) }}" target="_blank" class="table-btn-action" title="Cetak Bukti">
                    <i class="bi bi-receipt"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted-green py-4">Belum ada transaksi setoran.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $setoran->firstItem() ?? 0 }} - {{ $setoran->lastItem() ?? 0 }} dari {{ $setoran->total() }} transaksi
        </span>
        {{ $setoran->links() }}
      </div>
    </div>

@endsection
