<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $deadline = $this->order->expires_at?->format('d M Y, H:i') ?? '24 Jam';

        return (new MailMessage)
            ->subject("Pesanan Baru Dibuat #{$this->order->number} — BloomingKembang")
            ->greeting("Halo, {$this->order->customer_name}!")
            ->line("Terima kasih telah berbelanja di BloomingKembang. Pesanan Anda dengan nomor #{$this->order->number} telah berhasil dibuat.")
            ->line('Total Pembayaran: Rp '.number_format($this->order->grand_total, 0, ',', '.'))
            ->line("Batas Waktu Pembayaran: {$deadline} WIB")
            ->action('Lihat Detail & Bayar', url("/checkout/sukses/{$this->order->number}"))
            ->line('Pesanan akan otomatis dibatalkan jika pembayaran tidak diselesaikan sebelum batas waktu.');
    }

    /**
     * Get the WhatsApp formatted message template.
     */
    public function toWhatsApp(object $notifiable): string
    {
        $deadline = $this->order->expires_at?->format('d M Y, H:i') ?? '24 Jam';
        $total = 'Rp '.number_format($this->order->grand_total, 0, ',', '.');

        return "🌸 *BloomingKembang — Pesanan Dibuat*\n\n".
            "Halo *{$this->order->customer_name}*,\n".
            "Pesanan Anda *#{$this->order->number}* telah diterima.\n\n".
            "💰 *Total:* {$total}\n".
            "⏰ *Batas Bayar:* {$deadline} WIB\n\n".
            'Lanjutkan pembayaran Anda melalui tautan berikut:\n'.
            url("/checkout/sukses/{$this->order->number}")."\n\n".
            'Terima kasih telah mempercayakan koleksi hijab terbaik kepada kami!';
    }
}
