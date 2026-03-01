<?php

namespace App\Filament\Widgets;

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
                ->url('asd'),
        ];
    }
}
