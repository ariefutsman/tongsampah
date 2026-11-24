@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

    <div class="page-header">
      <div>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle">Perbarui data akun {{ $user->name }}.</p>
      </div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted-green">Kelola User</a></li>
          <li class="breadcrumb-item active text-main" aria-current="page">Edit</li>
        </ol>
      </nav>
    </div>

    <div class="card border-light shadow-sm p-4" style="max-width: 640px;">
      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label-custom">Nama Lengkap</label>
          <input type="text" name="name" class="form-control-custom" value="{{ old('name', $user->name) }}" required>
          @error('name') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Email</label>
          <input type="email" name="email" class="form-control-custom" value="{{ old('email', $user->email) }}" required>
          @error('email') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">No. HP</label>
          <input type="text" name="no_hp" class="form-control-custom" value="{{ old('no_hp', $user->no_hp) }}">
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Password Baru (opsional)</label>
          <input type="password" name="password" class="form-control-custom" minlength="8" placeholder="Kosongkan jika tidak diganti">
          @error('password') <div class="form-feedback-custom invalid-custom">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label-custom">Role</label>
          <select name="role" id="roleSelect" class="form-select-custom" {{ $hasHistory ? 'disabled' : '' }} required>
            <option value="admin" {{ $user->role->value === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="petugas" {{ $user->role->value === 'petugas' ? 'selected' : '' }}>Petugas</option>
            <option value="nasabah" {{ $user->role->value === 'nasabah' ? 'selected' : '' }}>Nasabah</option>
          </select>
          @if ($hasHistory)
            <input type="hidden" name="role" value="{{ $user->role->value }}">
            <span class="text-muted">Role tidak bisa diubah karena user ini sudah punya riwayat transaksi.</span>
          @endif
        </div>

        <div class="mb-3" id="alamatField" style="{{ $user->isNasabah() ? '' : 'display:none;' }}">
          <label class="form-label-custom">Alamat</label>
          <textarea name="alamat" class="form-control-custom" rows="3">{{ old('alamat', $user->nasabah->alamat ?? '') }}</textarea>
        </div>

        <div class="form-switch-custom mb-3">
          <input class="form-switch-input-custom" type="checkbox" name="is_active" value="1" id="isActive" {{ $user->is_active ? 'checked' : '' }}>
          <label class="form-switch-label" for="isActive">Akun aktif</label>
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
    if (!roleSelect || roleSelect.disabled) return;

    function toggleAlamat() {
      alamatField.style.display = roleSelect.value === 'nasabah' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleAlamat);
    toggleAlamat();
  });
</script>
@endpush
