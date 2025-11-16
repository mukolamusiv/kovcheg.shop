<?php

namespace App\Filament\Resources\Custumers\Pages;

use App\Filament\Resources\Custumers\CustumerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustumer extends EditRecord
{
    protected static string $resource = CustumerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
