<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'no_hp' => $data['no_hp'],
                'password' => Hash::make($data['password']),
                'role' => UserRole::Nasabah->value,
                'is_active' => true,
            ]);

            Nasabah::create([
                'user_id' => $user->id,
                'alamat' => $data['alamat'],
                'saldo' => 0,
            ]);

            return $user;
        });

        $token = $user->createToken('flutter-app')->plainTextToken;

        return response()->json([
            'user' => new ProfileResource($user->load('nasabah')),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 422);
        }

        if (! $user->isNasabah()) {
            return response()->json([
                'message' => 'Akun ini bukan akun nasabah. Silakan gunakan dashboard web.',
            ], 403);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan.',
            ], 403);
        }

        $token = $user->createToken('flutter-app')->plainTextToken;

        return response()->json([
            'user' => new ProfileResource($user->load('nasabah')),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }
}
