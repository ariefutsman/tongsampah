@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Kelola User</h1>
        <p class="page-subtitle">Kelola akun admin, petugas, dan nasabah.</p>
      </div>
      <a href="{{ route('admin.users.create') }}" class="btn-date-picker">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah User</span>
      </a>
    </div>

    <div class="table-card-custom">
      <div class="table-header-control">
        <form method="GET" class="table-search-box">
          <i class="bi bi-search table-search-icon"></i>
          <input type="text" name="search" class="table-search-input" placeholder="Cari nama, email, no. HP..." value="{{ request('search') }}">
          @if (request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
          @endif
        </form>
        <div class="table-filter-group">
          <div class="dropdown">
            <button class="btn-table-action dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-funnel"></i> {{ request('role') ? ucfirst(request('role')) : 'Semua Role' }}
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Semua Role</a></li>
              <li><a class="dropdown-item" href="{{ route('admin.users.index', ['role' => 'admin']) }}">Admin</a></li>
              <li><a class="dropdown-item" href="{{ route('admin.users.index', ['role' => 'petugas']) }}">Petugas</a></li>
              <li><a class="dropdown-item" href="{{ route('admin.users.index', ['role' => 'nasabah']) }}">Nasabah</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Email</th>
              <th>No. HP</th>
              <th>Role</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $user)
              <tr>
                <td class="table-user-name">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_hp ?? '-' }}</td>
                <td><span class="badge-table pending">{{ $user->role->label() }}</span></td>
                <td>
                  <span class="badge-table {{ $user->is_active ? 'success' : 'failed' }}">
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('admin.users.edit', $user) }}" class="table-btn-action" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                    @if ($user->id !== auth()->id() && $user->is_active)
                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('Nonaktifkan user {{ $user->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="table-btn-action delete" title="Nonaktifkan">
                          <i class="bi bi-slash-circle"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted-green py-4">Belum ada user.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
        </span>
        {{ $users->links() }}
      </div>
    </div>

@endsection
