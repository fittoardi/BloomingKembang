<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public ?Payment $payment = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $method = $this->payment?->method ? strtoupper($this->payment->method) : 'Online Payment';

        return (new MailMessage)
            ->subject("Pembayaran Berhasil untuk Pesanan #{$this->order->number} — BloomingKembang")
            ->greeting("Halo, {$this->order->customer_name}!")
            ->line("Kabar gembira! Pembayaran untuk pesanan #{$this->order->number} sebesar Rp ".number_format($this->order->grand_total, 0, ',', '.')." ({$method}) telah berhasil kami terima.")
            ->line('Pesanan Anda saat ini sedang disiapkan dengan cermat oleh tim BloomingKembang dan akan segera dikirimkan.')
            ->action('Lihat Status Pesanan', url("/akun/pesanan/{$this->order->number}"))
            ->line('Kami akan mengirimkan nomor resi pengiriman begitu paket diserahkan kepada kurir.');
    }

    /**
     * Get the WhatsApp formatted message template.
     */
    public function toWhatsApp(object $notifiable): string
    {
        $total = 'Rp '.number_format($this->order->grand_total, 0, ',', '.');
        $method = $this->payment?->method ? strtoupper($this->payment->method) : 'Online';

        return "🌸 *BloomingKembang — Pembayaran Berhasil!*\n\n".
            "Halo *{$this->order->customer_name}*,\n".
            "Pembayaran untuk pesanan *#{$this->order->number}* sebesar *{$total}* ({$method}) telah kami terima.\n\n".
            "📦 *Status:* Sedang Diproses & Dikemas\n\n".
            'Pantau status pesanan Anda di sini:\n'.
            url("/akun/pesanan/{$this->order->number}")."\n\n".
            'Terima kasih atas kepercayaannya!';
    }
}
