<?php

namespace App\Filament\Resources\Suppliners\Pages;

use App\Filament\Resources\Suppliners\SupplinerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSuppliners extends ListRecords
{
    protected static string $resource = SupplinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
