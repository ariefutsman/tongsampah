@extends('layouts.admin')

@section('title', 'Item Sampah — ' . $kategori->nama_kategori)

@section('content')

    <div class="page-header">
      <div>
        <a href="{{ route('admin.kategori.index') }}" class="text-muted-green text-decoration-none d-inline-block mb-1">
          <i class="bi bi-arrow-left"></i> Kembali ke Kategori Sampah
        </a>
        <h1 class="page-title">Item Sampah — {{ $kategori->nama_kategori }}</h1>
        <p class="page-subtitle">Kelola item sampah beserta harga beli per kg di kategori ini.</p>
      </div>
      <button type="button" class="btn-date-picker" data-bs-toggle="modal" data-bs-target="#modalCreateItem">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Item</span>
      </button>
    </div>

    <div class="table-card-custom">
      <div class="table-responsive">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Nama Item</th>
              <th>Harga / kg</th>
              <th>Satuan</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($items as $item)
              <tr>
                <td>{{ $item->nama_item }}</td>
                <td>Rp{{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                <td>{{ $item->satuan }}</td>
                <td>
                  <span class="badge-table {{ $item->is_active ? 'success' : 'failed' }}">
                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-1">
                    <button type="button" class="table-btn-action" title="Edit"
                      data-bs-toggle="modal" data-bs-target="#modalEditItem{{ $item->id }}">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('admin.kategori.item.destroy', [$kategori, $item]) }}" method="POST"
                      onsubmit="return confirm('Hapus item {{ $item->nama_item }}?');">
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
              <div class="modal fade" id="modalEditItem{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('admin.kategori.item.update', [$kategori, $item]) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Item Sampah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label-custom">Nama Item</label>
                          <input type="text" name="nama_item" class="form-control-custom" value="{{ $item->nama_item }}" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label-custom">Harga per kg (Rp)</label>
                          <input type="number" name="harga_per_kg" class="form-control-custom" value="{{ $item->harga_per_kg }}" min="0" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label-custom">Satuan</label>
                          <input type="text" name="satuan" class="form-control-custom" value="{{ $item->satuan }}" required>
                        </div>
                        <div class="form-switch-custom">
                          <input class="form-switch-input-custom" type="checkbox" name="is_active" value="1"
                            id="editItemActive{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                          <label class="form-switch-label" for="editItemActive{{ $item->id }}">Item aktif</label>
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
                <td colspan="5" class="text-center text-muted-green py-4">Belum ada item sampah di kategori ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-control">
        <span class="table-pagination-info">
          Menampilkan {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} item
        </span>
        {{ $items->links() }}
      </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="modalCreateItem" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('admin.kategori.item.store', $kategori) }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Tambah Item Sampah</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label-custom">Nama Item</label>
                <input type="text" name="nama_item" class="form-control-custom" placeholder="Contoh: Kardus (Box)" required>
              </div>
              <div class="mb-3">
                <label class="form-label-custom">Harga per kg (Rp)</label>
                <input type="number" name="harga_per_kg" class="form-control-custom" placeholder="1600" min="0" required>
              </div>
              <div class="mb-3">
                <label class="form-label-custom">Satuan</label>
                <input type="text" name="satuan" class="form-control-custom" value="kg" required>
              </div>
              <div class="form-switch-custom">
                <input class="form-switch-input-custom" type="checkbox" name="is_active" value="1" id="createItemActive" checked>
                <label class="form-switch-label" for="createItemActive">Item aktif</label>
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
