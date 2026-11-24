<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nasabah extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'alamat',
        'saldo',
    ];

    protected function casts(): array
    {
        return [
            'saldo' => 'decimal:0',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaksiSetor(): HasMany
    {
        return $this->hasMany(TransaksiSetor::class);
    }

    public function transaksiTarik(): HasMany
    {
        return $this->hasMany(TransaksiTarik::class);
    }
}
