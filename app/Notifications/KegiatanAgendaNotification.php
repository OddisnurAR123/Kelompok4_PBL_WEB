<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\AgendaModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanEksternalModel;
use Carbon\Carbon;

class KegiatanAgendaNotification extends Notification
{
    use Queueable;

    protected $agenda;
    protected $kegiatan;
    protected $kegiatanEksternal;

    // Constructor untuk menerima agenda, kegiatan, dan kegiatan eksternal yang relevan
    public function __construct($agenda, $kegiatan, $kegiatanEksternal)
    {
        $this->agenda = $agenda;
        $this->kegiatan = $kegiatan;
        $this->kegiatanEksternal = $kegiatanEksternal;
    }

    // Channel notifikasi
    public function via($notifiable)
    {
        return ['database', 'mail']; // Menyertakan database dan email
    }

    // Konten untuk notifikasi melalui database
    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'agenda_id' => $this->agenda->id_agenda,
            'agenda_name' => $this->agenda->nama_agenda,
            'agenda_date' => $this->agenda->tanggal_agenda,
            'kegiatan_name' => $this->kegiatan->nama_kegiatan,
            'kegiatan_date' => $this->kegiatan->tanggal_mulai,
            'kegiatan_eksternal_name' => $this->kegiatanEksternal->nama_kegiatan,
            'kegiatan_eksternal_date' => $this->kegiatanEksternal->waktu_kegiatan,
        ]);
    }

    // Konten notifikasi via email
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Notifikasi Kegiatan dan Agenda Terdekat')
                    ->line('Anda memiliki kegiatan dan agenda terdekat yang perlu dihadiri.')
                    ->line('Agenda: ' . $this->agenda->nama_agenda . ' pada ' . Carbon::parse($this->agenda->tanggal_agenda)->format('d-m-Y'))
                    ->line('Kegiatan: ' . $this->kegiatan->nama_kegiatan . ' pada ' . Carbon::parse($this->kegiatan->tanggal_mulai)->format('d-m-Y'))
                    ->line('Kegiatan Eksternal: ' . $this->kegiatanEksternal->nama_kegiatan . ' pada ' . Carbon::parse($this->kegiatanEksternal->waktu_kegiatan)->format('d-m-Y'))
                    ->action('Lihat Detail', url('/kegiatan/' . $this->kegiatan->id_kegiatan))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }
}
