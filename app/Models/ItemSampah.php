<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemSampah extends Model
{
    protected $table = 'item_sampah';

    protected $fillable = [
        'kategori_id',
        'nama_item',
        'harga_per_kg',
        'satuan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga_per_kg' => 'decimal:0',
            'is_active' => 'boolean',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSampah::class, 'kategori_id');
    }

    public function transaksiSetor(): HasMany
    {
        return $this->hasMany(TransaksiSetor::class, 'item_id');
    }
}
