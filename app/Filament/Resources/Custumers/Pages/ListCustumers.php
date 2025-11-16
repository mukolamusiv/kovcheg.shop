<?php

namespace App\Filament\Resources\Custumers\Pages;

use App\Filament\Resources\Custumers\CustumerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustumers extends ListRecords
{
    protected static string $resource = CustumerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
