<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Production;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProfitWidget extends ChartWidget
{
    protected ?string $heading = 'Profit Widget';

    protected function getData(): array
    {


    $orders = Production::selectRaw('MONTH(created_at) as month, SUM(total_cost) as total_price, SUM(cost_price) as cost_price')
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->get()
                ->map(function ($order) {
                    return [
                        'month' => \Illuminate\Support\Carbon::create()->month($order->month)->locale('uk')->startOfMonth()->translatedFormat('F'),
                        'profit' => $order->total_price - $order->cost_price,
                    ];
                })
                ->toArray();

            return [
                'datasets' => [
                    [
                        'label' => 'Чистий прибуток',
                        'data' => array_column($orders, 'profit'),
                        'backgroundColor' => '#10b981',
                    ],
                ],
                'labels' => array_column($orders, 'month'),
            ];
    }

    protected function getType(): string
    {
       // return 'pie';
        return 'bar';
    }
}
