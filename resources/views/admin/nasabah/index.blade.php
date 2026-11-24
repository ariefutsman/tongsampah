@extends('layouts.admin')

@section('title', 'Cari Nasabah')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Cari Nasabah</h1>
        <p class="page-subtitle">Cari data nasabah berdasarkan nama, no. HP, atau ID.</p>
      </div>
    </div>

    <div class="table-card-custom">
      <div class="table-header-control">
        <form method="GET" class="table-search-box">
          <i class="bi bi-search table-search-icon"></i>
          <input type="text" name="search" class="table-search-input" placeholder="Nama, no. HP, atau ID nasabah..." value="{{ request('search') }}">
        </form>
      </div>

      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>No. HP</th>
              <th>Saldo</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($nasabah as $item)
              <tr>
                <td>#{{ $item->id }}</td>
                <td class="table-user-name">{{ $item->user->name }}</td>
                <td>{{ $item->user->no_hp ?? '-' }}</td>
                <td class="table-amount">Rp{{ number_format($item->saldo, 0, ',', '.') }}</td>
                <td>
                  <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('admin.nasabah.show', $item) }}" class="table-btn-action" title="Lihat profil">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.setoran.create', ['nasabah_id' => $item->id]) }}" class="table-btn-action" title="Input Setoran">
                      <i class="bi bi-box-arrow-in-down"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted-green py-4">Nasabah tidak ditemukan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $nasabah->firstItem() ?? 0 }} - {{ $nasabah->lastItem() ?? 0 }} dari {{ $nasabah->total() }} nasabah
        </span>
        {{ $nasabah->links() }}
      </div>
    </div>

@endsection
