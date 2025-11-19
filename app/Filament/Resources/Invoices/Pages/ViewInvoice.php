<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Account;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('copy_invoice')
                ->label('Копіювати накладну')
                ->button()
                ->color('success')
                ->action(function () {
                    $newInvoice = $this->record->replicate();
                    $newInvoice->status = 'новий';
                    $newInvoice->save();

                    foreach ($this->record->items as $item) {
                        $newItem = $item->replicate();
                        $newItem->invoice_id = $newInvoice->id;
                        $newItem->save();
                    }

                    Notification::make()
                        ->title('Накладну успішно скопійовано.')
                        ->success()
                        ->send();
                }),

            EditAction::make(),
            Action::make('pay_invoice')
                ->label('Оплатити накладну')
                ->button()
                ->hidden(fn () => $this->record->status === 'оплачено')
                ->color('success')
                ->form([
                    Select::make('account_id')
                        ->label('Рахунок')
                        ->options(Account::all()->pluck('account_name', 'id'))
                        ->required(),
                    TextInput::make('amount')
                        ->label('Сума оплати')
                        ->numeric()
                        ->default(fn () => $this->record->total_amount - $this->record->paid_for())
                        ->required(),
                ])
                ->action(function (array $data) {
                    if(Account::find($data['account_id'])->balance < $data['amount']){
                        Notification::make()
                            ->title('Недостатньо коштів на рахунку для оплати накладної.')
                            ->danger()
                            ->send();
                        return;
                    }
                    Transaction::create([
                        'invoice_id' => $this->record->id,
                        'account_id' => $data['account_id'],
                        'transaction_type' => 'витрати',
                        'amount' => $data['amount'],
                        'transaction_date' => now(),
                        'description' => 'Оплата накладної #' . $this->record->invoice_number,
                        'user_id' => $this->record->supplier_id,
                        'manager_id' => auth()->id(),
                    ]);
                    if($this->record->paid_for() >= $this->record->total_amount)
                    {
                        $this->record->update(['status' => 'оплачено']);
                    }else if($this->record->paid_for() < $this->record->total_amount && $this->record->paid_for() > 0){
                        $this->record->update(['status' => 'частково оплачено']);
                    }

                    Notification::make()
                        ->title('Накладну оплачено успішно.')
                        ->success()
                        ->send();
                }),
            Action::make('recalculate_total_amount')
                ->label('Перерахувати суму накладної')
                ->button()
                ->color('warning')
                ->action(fn () => $this->record->updateCalculation()),

                Action::make('move_to_warehouse')
                    ->label('Перемістити матеріали на склад')
                    ->button()
                    ->hidden(fn () => $this->record->items->contains(fn ($item) => $item->movent_date != null))
                    ->color('info')
                    ->action(function () {
                        foreach ($this->record->items as $item) {
                            $item->movet();
                        }

                        //$this->record->update(['status' => 'переміщено']);
                        // Notification::make()
                        //     ->title('Матеріали успішно переміщено на склад.')
                        //     ->success()
                        //     ->send();
                    }),
        ];
    }
}
