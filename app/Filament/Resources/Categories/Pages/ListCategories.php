<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tree_view')
                ->label('Дерево категорій')
                ->color('success')
                ->icon('heroicon-o-squares-2x2')
                ->url(fn (): string => TreeCategories::getUrl()),
            CreateAction::make(),
        ];
    }
}
