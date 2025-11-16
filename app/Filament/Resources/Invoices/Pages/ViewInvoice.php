<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('pay_invoice')
                ->label('Оплатити накладну')
                ->button()
                ->color('success')
                ->action(fn () => $this->payInvoice()),
            Action::make('recalculate_total_amount')
                ->label('Перерахувати суму накладної')
                ->button()
                ->color('success')
                ->action(fn () => $this->record->updateCalculation()),
        ];
    }
}
