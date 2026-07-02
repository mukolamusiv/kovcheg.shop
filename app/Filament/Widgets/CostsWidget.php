<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Salaries\SalaryResource;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CostsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Додати витрати', '')
                ->description('Додати нові витрати')
                ->color('danger')
                ->url(TransactionResource::getUrl('costs')),
            Stat::make('Виплатити зарплату', '')
                ->description('Провести виплату зарплати')
                ->color('success')
                ->url(SalaryResource::getUrl('pay')),
        ];
    }
}
