<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
// use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewOrder extends ViewRecord
{

  //  use WithRecordNavigation;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // PreviousRecordAction::make(),
            // NextRecordAction::make(),
            EditAction::make(),
            Action::make('addPayment')
                ->label('Внести оплату')
                ->color('success')
                //->icon('heroicon-o-cash')
                ->action(function () {
                    // Add your payment logic here
                }),
        ];
    }

}
