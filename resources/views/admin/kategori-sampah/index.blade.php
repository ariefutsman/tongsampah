@extends('layouts.admin')

@section('title', 'Kategori Sampah')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Kategori Sampah</h1>
        <p class="page-subtitle">Kelola kategori sampah. Harga & satuan diatur per item di dalam tiap kategori.</p>
      </div>
      <button type="button" class="btn-date-picker" data-bs-toggle="modal" data-bs-target="#modalCreateKategori">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Kategori</span>
      </button>
    </div>

    <div class="table-card-custom">
      <div class="table-header-control">
        <form method="GET" class="table-search-box">
          <i class="bi bi-search table-search-icon"></i>
          <input type="text" name="search" class="table-search-input" placeholder="Cari kategori..." value="{{ request('search') }}">
        </form>
      </div>

      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Nama Kategori</th>
              <th>Jumlah Item</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($kategori as $item)
              <tr>
                <td>{{ $item->nama_kategori }}</td>
                <td>{{ $item->items_count }} item</td>
                <td>
                  <span class="badge-table {{ $item->is_active ? 'success' : 'failed' }}">
                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('admin.kategori.item.index', $item) }}" class="table-btn-action" title="Kelola Item">
                      <i class="bi bi-list-ul"></i>
                    </a>
                    <button type="button" class="table-btn-action" title="Edit"
                      data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $item->id }}">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('admin.kategori.destroy', $item) }}" method="POST"
                      onsubmit="return confirm('Hapus kategori {{ $item->nama_kategori }}?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="table-btn-action delete" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class="modal fade" id="modalEditKategori{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('admin.kategori.update', $item) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori Sampah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label-custom">Nama Kategori</label>
                          <input type="text" name="nama_kategori" class="form-control-custom" value="{{ $item->nama_kategori }}" required>
                        </div>
                        <div class="form-switch-custom">
                          <input class="form-switch-input-custom" type="checkbox" name="is_active" value="1"
                            id="editActive{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                          <label class="form-switch-label" for="editActive{{ $item->id }}">Kategori aktif</label>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-login" style="width:auto;">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted-green py-4">Belum ada kategori sampah.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $kategori->firstItem() ?? 0 }} - {{ $kategori->lastItem() ?? 0 }} dari {{ $kategori->total() }} kategori
        </span>
        {{ $kategori->links() }}
      </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="modalCreateKategori" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Tambah Kategori Sampah</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label-custom">Nama Kategori</label>
                <input type="text" name="nama_kategori" class="form-control-custom" placeholder="Contoh: Plastik Khusus" required>
              </div>
              <div class="form-switch-custom">
                <input class="form-switch-input-custom" type="checkbox" name="is_active" value="1" id="createActive" checked>
                <label class="form-switch-label" for="createActive">Kategori aktif</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn-login" style="width:auto;">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

@endsection
