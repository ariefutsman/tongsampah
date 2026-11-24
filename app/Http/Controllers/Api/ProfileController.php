<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): ProfileResource
    {
        return new ProfileResource($request->user()->load('nasabah'));
    }

    public function update(Request $request): ProfileResource
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'no_hp' => ['sometimes', 'nullable', 'string', 'max:20'],
            'alamat' => ['sometimes', 'nullable', 'string'],
        ]);

        $user = $request->user();

        $user->update(collect($data)->only(['name', 'no_hp'])->toArray());

        if (array_key_exists('alamat', $data)) {
            $user->nasabah()->update(['alamat' => $data['alamat']]);
        }

        return new ProfileResource($user->fresh()->load('nasabah'));
    }

    public function saldo(Request $request): JsonResponse
    {
        return response()->json([
            'saldo' => $request->user()->nasabah->saldo,
        ]);
    }
}
