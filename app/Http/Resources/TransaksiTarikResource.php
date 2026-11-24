<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiTarikResource extends JsonResource
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
            'jumlah' => $this->jumlah,
            'status' => $this->status,
            'tanggal' => $this->tanggal,
            'processed_at' => $this->processed_at,
            'petugas_name' => $this->petugas?->name,
        ];
    }
}
