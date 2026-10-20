<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChartWidget extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status Pesanan';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = [
            OrderStatus::PendingPayment->value => 'Menunggu Bayar',
            OrderStatus::Processing->value => 'Diproses',
            OrderStatus::Shipped->value => 'Dikirim',
            OrderStatus::Completed->value => 'Selesai',
            OrderStatus::Cancelled->value => 'Dibatalkan',
        ];

        $counts = [];
        $labels = [];

        foreach ($statuses as $statusValue => $label) {
            $labels[] = $label;
            $counts[] = Order::where('status', $statusValue)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#f59e0b', // amber (pending)
                        '#3b82f6', // blue (processing)
                        '#6366f1', // indigo (shipped)
                        '#10b981', // emerald (completed)
                        '#ef4444', // red (cancelled)
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
