<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Expenses\ExpenseResource;
use App\Filament\Resources\Salaries\SalaryResource;
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
                ->url(ExpenseResource::getUrl('add')),
            Stat::make('Виплатити зарплату', '')
                ->description('Провести виплату зарплати')
                ->color('success')
                ->url(SalaryResource::getUrl('pay')),
        ];
    }
}
