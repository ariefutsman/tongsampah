<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\PenarikanMasihDiprosesException;
use App\Exceptions\SaldoTidakCukupException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiTarikResource;
use App\Services\PenarikanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransaksiTarikController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $penarikan = $request->user()->nasabah
            ->transaksiTarik()
            ->with('petugas')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15);

        return TransaksiTarikResource::collection($penarikan);
    }

    public function store(Request $request, PenarikanService $service): JsonResponse
    {
        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $tarik = $service->request($request->user()->nasabah, $data['jumlah']);
        } catch (SaldoTidakCukupException|PenarikanMasihDiprosesException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => new TransaksiTarikResource($tarik),
        ], 201);
    }
}
