<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Keamanan Akun - BSI Inventory System')
            // Kita pakai teks biasa saja agar tidak muncul kode error di Gmail
            ->greeting('BSI - Bank Syariah Indonesia')
            ->line('Assalamu’alaikum, ' . $notifiable->name)
            ->line('Kami mendeteksi adanya aktivitas login baru pada akun Anda di Sistem Monitoring Kartu ATM & Buku Tabungan BSI.')
            ->line('Waktu Login: ' . now()->format('d M Y, H:i') . ' WIB')
            ->action('Masuk ke Dashboard', url('/dashboard'))
            ->line('Jika ini bukan Anda, segera hubungi IT Support BSI KCP Padang Ulak Karang.')
            ->salutation('Wassalamu’alaikum, IT Support BSI');
    }
}