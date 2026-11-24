<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,petugas,nasabah'],
            'alamat' => ['nullable', 'string', 'required_if:role,nasabah'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'no_hp' => $data['no_hp'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($data['role'] === UserRole::Nasabah->value) {
                Nasabah::create([
                    'user_id' => $user->id,
                    'alamat' => $data['alamat'] ?? null,
                    'saldo' => 0,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $user->load('nasabah');
        $hasHistory = $user->isNasabah()
            && $user->nasabah
            && ($user->nasabah->transaksiSetor()->exists() || $user->nasabah->transaksiTarik()->exists());

        return view('admin.users.edit', compact('user', 'hasHistory'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,petugas,nasabah'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $hasHistory = $user->isNasabah()
            && $user->nasabah
            && ($user->nasabah->transaksiSetor()->exists() || $user->nasabah->transaksiTarik()->exists());

        DB::transaction(function () use ($data, $user, $hasHistory, $request) {
            $updatePayload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'no_hp' => $data['no_hp'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ];

            if (! empty($data['password'])) {
                $updatePayload['password'] = Hash::make($data['password']);
            }

            if (! $hasHistory) {
                $updatePayload['role'] = $data['role'];
            }

            $user->update($updatePayload);

            if ($user->isNasabah()) {
                $user->nasabah()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['alamat' => $data['alamat'] ?? null]
                );
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => false]);

        return back()->with('success', 'User berhasil dinonaktifkan.');
    }
}
