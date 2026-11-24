@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Tambah User</h1>
        <p class="page-subtitle">Buat akun admin, petugas, atau nasabah baru.</p>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted-green">Kelola User</a></li>
          <li class="breadcrumb-item active text-main" aria-current="page">Tambah</li>
        </ol>
      </nav>
    </div>

    <div class="card border-light shadow-sm p-4" style="max-width: 640px;">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label-custom">Nama Lengkap</label>
          <input type="text" name="name" class="form-control-custom" value="{{ old('name') }}" required>
          @error('name') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Email</label>
          <input type="email" name="email" class="form-control-custom" value="{{ old('email') }}" required>
          @error('email') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">No. HP</label>
          <input type="text" name="no_hp" class="form-control-custom" value="{{ old('no_hp') }}">
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Password</label>
          <input type="password" name="password" class="form-control-custom" required minlength="8">
          @error('password') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Role</label>
          <select name="role" id="roleSelect" class="form-select-custom" required>
            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role...</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
            <option value="nasabah" {{ old('role') === 'nasabah' ? 'selected' : '' }}>Nasabah</option>
          </select>
          @error('role') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3" id="alamatField" style="display: none;">
          <label class="form-label-custom">Alamat</label>
          <textarea name="alamat" class="form-control-custom" rows="3">{{ old('alamat') }}</textarea>
        </div>

        <div class="d-flex gap-2 mt-4">
          <button type="submit" class="btn-login" style="width:auto;">Simpan</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a>
        </div>
      </form>
    </div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('roleSelect');
    const alamatField = document.getElementById('alamatField');

    function toggleAlamat() {
      alamatField.style.display = roleSelect.value === 'nasabah' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleAlamat);
    toggleAlamat();
  });
</script>
@endpush
