<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterial extends ViewRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('download')
                ->label('Завантажити PDF')
                //->icon('heroicon-o-download')
                ->color('success')
                ->url(fn () => route('pdf.material', ['id' => $this->record->id])),
        ];
    }
}
