<?php

namespace App\Notifications;

use App\Models\TransaksiTarik;
use Illuminate\Notifications\Notification;

class PenarikanDisetujui extends Notification
{
    public function __construct(public TransaksiTarik $tarik)
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
            'type' => 'penarikan_disetujui',
            'title' => 'Penarikan disetujui',
            'message' => sprintf(
                'Pengajuan penarikan sebesar Rp%s telah disetujui.',
                number_format($this->tarik->jumlah, 0, ',', '.')
            ),
            'penarikan_id' => $this->tarik->id,
        ];
    }
}
