<?php

namespace App\Services;

use App\Exceptions\PenarikanMasihDiprosesException;
use App\Exceptions\PenarikanSudahDiprosesException;
use App\Exceptions\SaldoTidakCukupException;
use App\Models\Nasabah;
use App\Models\TransaksiTarik;
use App\Models\User;
use App\Notifications\PenarikanDisetujui;
use Illuminate\Support\Facades\DB;

class PenarikanService
{
    public function request(Nasabah $nasabah, int $jumlah): TransaksiTarik
    {
        return DB::transaction(function () use ($nasabah, $jumlah) {
            $nasabah = Nasabah::lockForUpdate()->findOrFail($nasabah->id);

            if ($nasabah->transaksiTarik()->where('status', 'pending')->exists()) {
                throw new PenarikanMasihDiprosesException();
            }

            if ($nasabah->saldo < $jumlah) {
                throw new SaldoTidakCukupException();
            }

            return TransaksiTarik::create([
                'nasabah_id' => $nasabah->id,
                'petugas_id' => null,
                'jumlah' => $jumlah,
                'status' => 'pending',
                'tanggal' => now()->toDateString(),
            ]);
        });
    }

    public function approve(TransaksiTarik $tarik, User $petugas): TransaksiTarik
    {
        return DB::transaction(function () use ($tarik, $petugas) {
            $tarik = TransaksiTarik::lockForUpdate()->findOrFail($tarik->id);

            if ($tarik->status !== 'pending') {
                throw new PenarikanSudahDiprosesException();
            }

            $nasabah = Nasabah::lockForUpdate()->findOrFail($tarik->nasabah_id);

            if ($nasabah->saldo < $tarik->jumlah) {
                throw new SaldoTidakCukupException();
            }

            $nasabah->decrement('saldo', $tarik->jumlah);

            $tarik->update([
                'status' => 'disetujui',
                'petugas_id' => $petugas->id,
                'processed_at' => now(),
            ]);

            $nasabah->user->notify(new PenarikanDisetujui($tarik));

            return $tarik;
        });
    }

    public function reject(TransaksiTarik $tarik, User $petugas): TransaksiTarik
    {
        return DB::transaction(function () use ($tarik, $petugas) {
            $tarik = TransaksiTarik::lockForUpdate()->findOrFail($tarik->id);

            if ($tarik->status !== 'pending') {
                throw new PenarikanSudahDiprosesException();
            }

            $tarik->update([
                'status' => 'ditolak',
                'petugas_id' => $petugas->id,
                'processed_at' => now(),
            ]);

            return $tarik;
        });
    }
}
