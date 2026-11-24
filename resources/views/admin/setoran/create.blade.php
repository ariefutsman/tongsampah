@extends('layouts.admin')

@section('title', 'Input Setoran')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Input Setoran</h1>
        <p class="page-subtitle">Catat setoran sampah dari nasabah.</p>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.setoran.index') }}" class="text-decoration-none text-muted-green">Setoran</a></li>
          <li class="breadcrumb-item active text-main" aria-current="page">Input</li>
        </ol>
      </nav>
    </div>

    <div class="card border-light shadow-sm p-4" style="max-width: 640px;">
      <form action="{{ route('admin.setoran.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label-custom">Nasabah</label>
          <select name="nasabah_id" id="nasabahSelect" class="form-select-custom" required>
            <option value="" disabled {{ $nasabahTerpilih ? '' : 'selected' }}>Pilih nasabah...</option>
            @foreach ($nasabahList as $item)
              <option value="{{ $item->id }}" {{ $nasabahTerpilih && $nasabahTerpilih->id === $item->id ? 'selected' : '' }}>
                {{ $item->user->name }} ({{ $item->user->no_hp ?? 'no HP -' }}) — Saldo: Rp{{ number_format($item->saldo, 0, ',', '.') }}
              </option>
            @endforeach
          </select>
          @error('nasabah_id') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Item Sampah</label>
          <select name="item_id" id="itemSelect" class="form-select-custom" required>
            <option value="" disabled selected>Pilih item sampah...</option>
            @foreach ($kategoriList as $kategori)
              @if ($kategori->items->isNotEmpty())
                <optgroup label="{{ $kategori->nama_kategori }}">
                  @foreach ($kategori->items as $item)
                    <option value="{{ $item->id }}" data-harga="{{ $item->harga_per_kg }}">
                      {{ $item->nama_item }} — Rp{{ number_format($item->harga_per_kg, 0, ',', '.') }}/{{ $item->satuan }}
                    </option>
                  @endforeach
                </optgroup>
              @endif
            @endforeach
          </select>
          @error('item_id') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Berat (kg)</label>
          <input type="number" step="0.01" min="0.01" name="berat_kg" id="beratInput" class="form-control-custom" required>
          @error('berat_kg') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Tanggal</label>
          <input type="date" name="tanggal" class="form-control-custom" value="{{ old('tanggal', now()->toDateString()) }}">
        </div>

        <div class="alert alert-success" role="alert">
          Estimasi total nilai: <strong id="previewTotal">Rp0</strong>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn-login" style="width:auto;">Simpan Setoran</button>
          <a href="{{ route('admin.setoran.index') }}" class="btn btn-light">Batal</a>
        </div>
      </form>
    </div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const itemSelect = document.getElementById('itemSelect');
    const beratInput = document.getElementById('beratInput');
    const previewTotal = document.getElementById('previewTotal');

    function updatePreview() {
      const selected = itemSelect.options[itemSelect.selectedIndex];
      const harga = parseFloat(selected?.dataset?.harga || 0);
      const berat = parseFloat(beratInput.value || 0);
      const total = harga * berat;
      previewTotal.textContent = 'Rp' + total.toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }

    itemSelect.addEventListener('change', updatePreview);
    beratInput.addEventListener('input', updatePreview);
  });
</script>
@endpush
