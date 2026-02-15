<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
// use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewMaterial extends ViewRecord
{

  //use WithRecordNavigation;

    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // PreviousRecordAction::make(),
            // NextRecordAction::make(),
            EditAction::make(),
            Action::make('download')
                ->label('Завантажити PDF')
                //->icon('heroicon-o-download')
                ->color('success')
                ->url(fn () => route('pdf.material', ['id' => $this->record->id])),
        ];
    }
}
