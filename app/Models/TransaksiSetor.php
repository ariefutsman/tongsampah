<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiSetor extends Model
{
    protected $table = 'transaksi_setor';

    protected $fillable = [
        'nasabah_id',
        'petugas_id',
        'item_id',
        'berat_kg',
        'total_nilai',
        'tanggal',
    ];

    protected function casts(): array
    {
        return [
            'berat_kg' => 'decimal:2',
            'total_nilai' => 'decimal:0',
            'tanggal' => 'date',
        ];
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemSampah::class, 'item_id');
    }
}
