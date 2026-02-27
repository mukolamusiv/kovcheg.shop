<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TransactionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Transaction Chart Widget';

    protected function getData(): array
    {

        $transaction = Transaction::selectRaw('MONTH(created_at) as month, transaction_type, COUNT(*) as count, SUM(amount) as amount')
            ->groupBy(DB::raw('MONTH(created_at), transaction_type'))
            ->orderBy('month')
            ->get()
            ->groupBy('transaction_type')
            ->map(function ($groupedTransactions) {
            return $groupedTransactions->mapWithKeys(function ($transaction) {
                return [
                $transaction->month => [
                    'month' => \Illuminate\Support\Carbon::create()->month($transaction->month)->locale('uk')->startOfMonth()->translatedFormat('F'),
                    'count' => $transaction->count,
                    'amount' => $transaction->amount,
                ],
                ];
            });
            })
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Надходження',
                    'data' => array_column($transaction['надходження'] ?? [], 'amount'),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Витрати',
                    'data' => array_column($transaction['витрати'] ?? [], 'amount'),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => array_column($transaction['надходження'] ?? $transaction['витрати'] ?? [], 'month'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
