<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Запланувати витрату'),
            Action::make('add')
                ->label('Додати витрату')
                ->color('danger')
                ->url(ExpenseResource::getUrl('add')),
        ];
    }
}
