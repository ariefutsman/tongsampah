<?php

namespace Database\Seeders;

use App\Models\ItemSampah;
use App\Models\Nasabah;
use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds. Local/dev only — seeds a petugas account,
     * sample nasabah, and sample transactions so the dashboard, CRUD
     * screens, and reports have realistic data to show.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@tongsampah.test'],
            [
                'name' => 'Petugas Bank Sampah',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'no_hp' => '081200000001',
            ]
        );

        $itemList = ItemSampah::where('is_active', true)->get();

        if ($itemList->isEmpty()) {
            return;
        }

        $nasabahList = collect();

        $namaNasabah = [
            'Siti Aminah', 'Budi Santoso', 'Rina Wulandari', 'Agus Setiawan', 'Dewi Lestari',
            'Hendra Gunawan', 'Yuni Kartika', 'Joko Prasetyo', 'Maya Sari', 'Fajar Nugroho',
        ];

        foreach ($namaNasabah as $index => $nama) {
            $user = User::firstOrCreate(
                ['email' => 'nasabah'.($index + 1).'@tongsampah.test'],
                [
                    'name' => $nama,
                    'password' => Hash::make('password'),
                    'role' => 'nasabah',
                    'no_hp' => '0812'.str_pad((string) ($index + 1), 8, '0', STR_PAD_LEFT),
                ]
            );

            $nasabah = Nasabah::firstOrCreate(
                ['user_id' => $user->id],
                ['alamat' => 'Jl. Contoh No. '.($index + 1).', RT 0'.rand(1, 9).'/RW 0'.rand(1, 9)]
            );

            $nasabahList->push($nasabah);
        }

        // Sebar ~50 transaksi setoran dalam 3 bulan terakhir.
        for ($i = 0; $i < 50; $i++) {
            $nasabah = $nasabahList->random();
            $item = $itemList->random();
            $beratKg = round(mt_rand(5, 200) / 10, 2);
            $totalNilai = $beratKg * $item->harga_per_kg;
            $tanggal = now()->subDays(rand(0, 90));

            $setoran = TransaksiSetor::create([
                'nasabah_id' => $nasabah->id,
                'petugas_id' => $petugas->id,
                'item_id' => $item->id,
                'berat_kg' => $beratKg,
                'total_nilai' => $totalNilai,
                'tanggal' => $tanggal->toDateString(),
            ]);

            $nasabah->increment('saldo', $totalNilai);
        }

        // ~10 pengajuan penarikan dengan status bervariasi.
        for ($i = 0; $i < 10; $i++) {
            $nasabah = Nasabah::find($nasabahList->random()->id);
            $jumlah = min($nasabah->saldo, mt_rand(5, 50) * 1000);

            if ($jumlah <= 0) {
                continue;
            }

            $status = collect(['pending', 'disetujui', 'ditolak'])->random();
            $tanggal = now()->subDays(rand(0, 60));

            $tarik = TransaksiTarik::create([
                'nasabah_id' => $nasabah->id,
                'petugas_id' => $status === 'pending' ? null : $petugas->id,
                'jumlah' => $jumlah,
                'status' => $status,
                'tanggal' => $tanggal->toDateString(),
                'processed_at' => $status === 'pending' ? null : $tanggal,
            ]);

            if ($status === 'disetujui') {
                $nasabah->decrement('saldo', $jumlah);
            }
        }
    }
}
