<?php

namespace App\Notifications;

use App\Models\TransaksiSetor;
use Illuminate\Notifications\Notification;

class SetoranTercatat extends Notification
{
    public function __construct(public TransaksiSetor $setoran)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'setoran_tercatat',
            'title' => 'Setoran berhasil dicatat',
            'message' => sprintf(
                'Setoran %s sebesar %s kg (%s) telah dicatat. Saldo Anda bertambah Rp%s.',
                $this->setoran->item->nama_item,
                number_format($this->setoran->berat_kg, 2),
                $this->setoran->tanggal->translatedFormat('d M Y'),
                number_format($this->setoran->total_nilai, 0, ',', '.')
            ),
            'setoran_id' => $this->setoran->id,
        ];
    }
}
