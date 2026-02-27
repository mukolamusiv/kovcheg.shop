<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderChartWidget extends ChartWidget
{
    protected ?string $heading = 'Order Chart Widget';

    protected function getData(): array
    {
        $orders = Order::selectRaw('MONTH(created_at) as month, status, COUNT(*) as count, SUM(paid_amount) as paid_amount')
            ->groupBy(DB::raw('MONTH(created_at)'), 'status')
            ->orderBy('month')
            ->get()
            ->groupBy('status')
            ->map(function ($groupedOrders, $status) {
            return $groupedOrders->mapWithKeys(function ($order) use ($status) {
                return [
                    $order->month => [
                        'month' => \Illuminate\Support\Carbon::create()->month($order->month)->locale('uk')->startOfMonth()->translatedFormat('F'),
                        'status' => $status,
                        'count' => $order->count,
                        //'paid_amount' => $order->paid_amount,
                    ],
                ];
            });
            })
            ->toArray();

       // dd($orders);

        return [
            'datasets' => array_map(function ($status) use ($orders) {
                return [
                    'label' => ucfirst($status),
                    'data' => array_column($orders[$status] ?? [], 'count'),
                    'backgroundColor' => match ($status) {
                        'оплачено' => 'rgba(75, 192, 192, 0.2)',
                        'частково оплачено' => 'rgba(255, 206, 86, 0.2)',
                        'очікується' => 'rgba(255, 99, 132, 0.2)',
                        default => 'rgba(201, 203, 207, 0.2)',
                    },
                    'borderColor' => match ($status) {
                        'оплачено' => 'rgba(75, 192, 192, 1)',
                        'частково оплачено' => 'rgba(255, 206, 86, 1)',
                        'очікується' => 'rgba(255, 99, 132, 1)',
                        default => 'rgba(201, 203, 207, 1)',
                    },
                    'borderWidth' => 1,
                ];
            }, array_keys($orders)),
            'labels' => array_column($orders[array_key_first($orders)] ?? [], 'month'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
