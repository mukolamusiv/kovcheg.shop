<?php

namespace App\Filament\Resources\Custumers\Pages;

use App\Filament\Resources\Custumers\CustumerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustumer extends ViewRecord
{
    protected static string $resource = CustumerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
