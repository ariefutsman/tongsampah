<?php

namespace App\Services;

use App\Models\ItemSampah;
use App\Models\Nasabah;
use App\Models\TransaksiSetor;
use App\Notifications\SetoranTercatat;
use Illuminate\Support\Facades\DB;

class SetoranService
{
    public function store(array $data): TransaksiSetor
    {
        return DB::transaction(function () use ($data) {
            $item = ItemSampah::findOrFail($data['item_id']);
            $nasabah = Nasabah::lockForUpdate()->findOrFail($data['nasabah_id']);

            $totalNilai = $data['berat_kg'] * $item->harga_per_kg;

            $setoran = TransaksiSetor::create([
                'nasabah_id' => $nasabah->id,
                'petugas_id' => $data['petugas_id'],
                'item_id' => $item->id,
                'berat_kg' => $data['berat_kg'],
                'total_nilai' => $totalNilai,
                'tanggal' => $data['tanggal'] ?? now()->toDateString(),
            ]);
            $setoran->setRelation('item', $item);

            $nasabah->increment('saldo', $totalNilai);

            $nasabah->user->notify(new SetoranTercatat($setoran));

            return $setoran;
        });
    }
}
