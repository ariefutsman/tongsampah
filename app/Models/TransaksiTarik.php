<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiTarik extends Model
{
    protected $table = 'transaksi_tarik';

    protected $fillable = [
        'nasabah_id',
        'petugas_id',
        'jumlah',
        'status',
        'tanggal',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:0',
            'tanggal' => 'date',
            'processed_at' => 'datetime',
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
}
