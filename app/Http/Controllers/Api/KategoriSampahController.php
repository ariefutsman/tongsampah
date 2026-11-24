<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriSampahResource;
use App\Models\KategoriSampah;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KategoriSampahController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $kategori = KategoriSampah::where('is_active', true)
            ->with(['items' => fn ($q) => $q->where('is_active', true)->orderBy('nama_item')])
            ->orderBy('nama_kategori')
            ->get();

        return KategoriSampahResource::collection($kategori);
    }
}
