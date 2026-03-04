<?php

namespace App\Filament\Resources\Suppliners\Pages;

use App\Filament\Resources\Suppliners\SupplinerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSuppliner extends ViewRecord
{
    protected static string $resource = SupplinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
