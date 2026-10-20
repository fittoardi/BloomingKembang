<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->whereDate('paid_at', today())
            ->sum('grand_total');

        $thisMonthRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('grand_total');

        $pendingPaymentCount = Order::where('status', OrderStatus::PendingPayment)->count();
        $processingCount = Order::where('status', OrderStatus::Processing)->count();

        // 7 days revenue trend for sparkline chart
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $revenueTrend[] = (int) Order::where('payment_status', PaymentStatus::Paid)
                ->whereDate('paid_at', $date)
                ->sum('grand_total');
        }

        return [
            Stat::make('Omzet Hari Ini', 'Rp '.number_format($todayRevenue, 0, ',', '.'))
                ->description('Total penjualan lunas hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($revenueTrend)
                ->color('success'),

            Stat::make('Omzet Bulan Ini', 'Rp '.number_format($thisMonthRevenue, 0, ',', '.'))
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Menunggu Bayar', (string) $pendingPaymentCount)
                ->description('Pesanan belum diselesaikan')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPaymentCount > 0 ? 'warning' : 'gray'),

            Stat::make('Siap Dikirim / Diproses', (string) $processingCount)
                ->description('Perlu dikemas dan diberi resi')
                ->descriptionIcon('heroicon-m-truck')
                ->color($processingCount > 0 ? 'info' : 'gray'),
        ];
    }
}
