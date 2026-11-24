<?php

namespace Database\Seeders;

use App\Models\ItemSampah;
use App\Models\KategoriSampah;
use Illuminate\Database\Seeder;

class KategoriSampahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Kertasan' => [
                ['nama_item' => 'Kardus (Box)', 'harga_per_kg' => 1600],
                ['nama_item' => 'Arsip Putih (AP)', 'harga_per_kg' => 1500],
                ['nama_item' => 'Buku', 'harga_per_kg' => 1000],
                ['nama_item' => 'Arsip Sampah (AS) Cacah', 'harga_per_kg' => 500],
                ['nama_item' => 'Boncos (Kertas Warna)', 'harga_per_kg' => 300],
                ['nama_item' => 'Duplek/Majalah/LKS', 'harga_per_kg' => 500],
                ['nama_item' => 'Koran', 'harga_per_kg' => 1500],
            ],
            'Plastik Khusus' => [
                ['nama_item' => 'Botol Bersih (PET A) Bening', 'harga_per_kg' => 6500],
                ['nama_item' => 'Botol Bersih (PET A) Biru Muda', 'harga_per_kg' => 5500],
                ['nama_item' => 'Botol Bersih (PET A) Mix A dan B', 'harga_per_kg' => 5500],
                ['nama_item' => 'Botol Kotor (PET C)', 'harga_per_kg' => 1000],
                ['nama_item' => 'Botol Warna (Ijo Sprite/Biru Mizone)', 'harga_per_kg' => 1500],
                ['nama_item' => 'Gelas Bersih (Gelas A)', 'harga_per_kg' => 5200],
                ['nama_item' => 'Gelas Kotor (Gelas B)', 'harga_per_kg' => 1500],
                ['nama_item' => 'Gelas Kopi', 'harga_per_kg' => 800],
                ['nama_item' => 'Gelas Montea', 'harga_per_kg' => 1200],
                ['nama_item' => 'Galon Le Mineral', 'harga_per_kg' => 3700],
                ['nama_item' => 'Galon Merek Aqua (Harga Satuan)', 'harga_per_kg' => 4500, 'satuan' => 'galon'],
                ['nama_item' => 'MLP (Lapisan Almunium)', 'harga_per_kg' => 300],
            ],
            'Plastik Umum' => [
                ['nama_item' => 'Emberan', 'harga_per_kg' => 1000],
                ['nama_item' => 'Emberan (Kristal)', 'harga_per_kg' => 4000],
                ['nama_item' => 'Emberan (Naso)', 'harga_per_kg' => 2000],
                ['nama_item' => 'Impeck', 'harga_per_kg' => 400],
                ['nama_item' => 'Paralon', 'harga_per_kg' => 500],
                ['nama_item' => 'Kabel', 'harga_per_kg' => 3000],
                ['nama_item' => 'Tutup Botol (HD)', 'harga_per_kg' => 2500],
                ['nama_item' => 'Tutup Galon Aqua', 'harga_per_kg' => 3500],
            ],
            'Logam Karat' => [
                ['nama_item' => 'Kaleng', 'harga_per_kg' => 1500],
                ['nama_item' => 'Besi', 'harga_per_kg' => 3200],
                ['nama_item' => 'Kerompong', 'harga_per_kg' => 1800],
                ['nama_item' => 'Wajan', 'harga_per_kg' => 6000],
            ],
            'Logam Non Karat' => [
                ['nama_item' => 'Almunium', 'harga_per_kg' => 10000],
            ],
            'Organik' => [
                ['nama_item' => 'Minyak Jelantah', 'harga_per_kg' => 6000, 'satuan' => 'liter'],
            ],
            'Gabrug/Campuran Non Organik' => [
                ['nama_item' => 'Gabrug/Campuran Non Organik', 'harga_per_kg' => 500],
            ],
        ];

        foreach ($data as $namaKategori => $items) {
            $kategori = KategoriSampah::updateOrCreate(
                ['nama_kategori' => $namaKategori],
                ['is_active' => true]
            );

            foreach ($items as $item) {
                ItemSampah::updateOrCreate(
                    ['kategori_id' => $kategori->id, 'nama_item' => $item['nama_item']],
                    [
                        'harga_per_kg' => $item['harga_per_kg'],
                        'satuan' => $item['satuan'] ?? 'kg',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
