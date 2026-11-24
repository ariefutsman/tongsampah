<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'alamat' => $this->whenLoaded('nasabah', fn () => $this->nasabah->alamat),
            'saldo' => $this->whenLoaded('nasabah', fn () => $this->nasabah->saldo),
            'created_at' => $this->created_at,
        ];
    }
}
