<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiSetorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransaksiSetorController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $setoran = $request->user()->nasabah
            ->transaksiSetor()
            ->with(['item.kategori', 'petugas'])
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15);

        return TransaksiSetorResource::collection($setoran);
    }
}
