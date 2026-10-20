<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderShippedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $courier = strtoupper($this->order->courier ?? 'Kurir');
        $resi = $this->order->tracking_number ?? '-';

        return (new MailMessage)
            ->subject("Pesanan #{$this->order->number} Telah Dikirim! — BloomingKembang")
            ->greeting("Halo, {$this->order->customer_name}!")
            ->line("Paket pesanan Anda #{$this->order->number} telah diserahkan kepada jasa ekspedisi {$courier}.")
            ->line("Nomor Resi: {$resi}")
            ->action('Lacak Pengiriman', url("/lacak-pesanan?order={$this->order->number}"))
            ->line('Estimasi waktu tiba bervariasi sesuai lokasi tujuan pengiriman.');
    }

    /**
     * Get the WhatsApp formatted message template.
     */
    public function toWhatsApp(object $notifiable): string
    {
        $courier = strtoupper($this->order->courier ?? 'Kurir');
        $resi = $this->order->tracking_number ?? '-';

        return "🌸 *BloomingKembang — Pesanan Telah Dikirim!*\n\n".
            "Halo *{$this->order->customer_name}*,\n".
            "Pesanan *#{$this->order->number}* telah diserahkan kepada kurir pengiriman.\n\n".
            "🚚 *Kurir:* {$courier}\n".
            "🏷️ *Nomor Resi:* {$resi}\n\n".
            'Lacak status pengiriman paket Anda di:\n'.
            url("/lacak-pesanan?order={$this->order->number}")."\n\n".
            'Terima kasih telah berbelanja di BloomingKembang!';
    }
}
