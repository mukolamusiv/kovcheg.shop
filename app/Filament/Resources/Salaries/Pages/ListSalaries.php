<?php

namespace App\Filament\Resources\Salaries\Pages;

use App\Filament\Resources\Salaries\SalaryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalaries extends ListRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Нарахувати зарплату'),
            Action::make('pay')
                ->label('Виплатити зарплату')
                ->color('success')
                ->url(SalaryResource::getUrl('pay')),
        ];
    }
}
