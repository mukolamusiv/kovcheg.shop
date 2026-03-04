<?php

namespace App\Filament\Resources\Suppliners\Pages;

use App\Filament\Resources\Suppliners\SupplinerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSuppliner extends EditRecord
{
    protected static string $resource = SupplinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
