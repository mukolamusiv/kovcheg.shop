<?php

namespace App\Filament\Resources\Productions\Pages;

use App\Filament\Resources\Productions\ProductionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
class ListProductions extends ListRecords
{
    protected static string $resource = ProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Всі' => Tab::make(),
            'Виготовлені' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'виготовляється')),
            'Готові' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'виготовлено')),
            'Шаблони' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_template', true)),
        ];
    }
}
