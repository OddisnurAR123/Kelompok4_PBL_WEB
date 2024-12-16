<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KegiatanAgendaNotification extends Notification
{
    use Queueable;

    /**
     * Instance dari kegiatan
     */
    private $kegiatan;

    public function __construct($kegiatan)
    {
        $this->kegiatan = $kegiatan;
    }

    /**
     * Tentukan saluran notifikasi (akan digunakan database).
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Mendapatkan representasi notifikasi dalam bentuk email.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('Ada notifikasi baru terkait kegiatan Anda.')
    //                 ->action('Lihat Kegiatan', url("/kegiatan/{$this->kegiatan->id_kegiatan}"))
    //                 ->line('Terima kasih telah menggunakan aplikasi kami!');
    // }

    /**
     * Mendapatkan representasi array dari notifikasi.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_kegiatan' => $this->kegiatan->id_kegiatan,
            'nama_kegiatan' => $this->kegiatan->nama_kegiatan,
            'tanggal_mulai' => $this->kegiatan->tanggal_mulai,
            'tanggal_selesai' => $this->kegiatan->tanggal_selesai,
            'message' => "Kegiatan '{$this->kegiatan->nama_kegiatan}' akan dimulai pada {$this->kegiatan->tanggal_mulai->format('d M Y H:i')}.",
        ];
    }
}
