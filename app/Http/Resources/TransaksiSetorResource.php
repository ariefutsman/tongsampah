<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiSetorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'berat_kg' => $this->berat_kg,
            'total_nilai' => $this->total_nilai,
            'tanggal' => $this->tanggal,
            'item' => [
                'id' => $this->item->id,
                'nama_item' => $this->item->nama_item,
                'satuan' => $this->item->satuan,
                'harga_per_kg' => $this->item->harga_per_kg,
                'kategori_nama' => $this->item->kategori->nama_kategori,
            ],
            'petugas_name' => $this->petugas->name,
        ];
    }
}
