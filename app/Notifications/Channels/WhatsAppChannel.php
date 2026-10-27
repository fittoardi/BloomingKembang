<?php

namespace App\Notifications\Channels;

use App\Models\Setting;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    /**
     * Send the given notification via WhatsApp.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (empty($message)) {
            return;
        }

        $rawPhone = $notifiable->routeNotificationFor('whatsapp', $notification)
            ?? $notifiable->phone
            ?? ($notification->order->customer_phone ?? null);

        if (! $rawPhone) {
            return;
        }

        $phone = $this->formatIndonesianPhoneNumber($rawPhone);
        $token = config('services.fonnte.token') ?? Setting::get('whatsapp.token') ?? env('FONNTE_TOKEN');

        // Jika token API tersedia dan bukan environment testing, kirim lewat Fonnte Gateway
        if ($token && ! app()->environment('testing')) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->timeout(10)->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $message,
                ]);

                if (! $response->successful()) {
                    Log::warning("Fonnte WhatsApp API warning [{$response->status()}]: ".$response->body());
                }
            } catch (\Throwable $e) {
                Log::error('Fonnte WhatsApp send error: '.$e->getMessage());
            }
        } else {
            // Fallback log untuk mode development / testing
            Log::info("WhatsApp Notification dispatched to [{$phone}]:\n{$message}");
        }
    }

    /**
     * Format phone number to standard international format (e.g. 628123456789).
     */
    protected function formatIndonesianPhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62'.substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62'.$cleaned;
        }

        return $cleaned;
    }
}
