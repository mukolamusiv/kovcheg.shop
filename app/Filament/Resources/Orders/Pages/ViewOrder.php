<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Account;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                ->button()
                ->hidden(fn () => $this->record->status === 'оплачено')
                ->color('success')
                ->form([
                    Select::make('account_id')
                        ->label('Зарахувати кошти на рахунок')
                        ->options(Account::all()->pluck('account_name', 'id'))
                        ->required(),
                    TextInput::make('amount')
                        ->label('Сума оплати')
                        ->numeric()
                        ->default(fn () => $this->record->total_amount - $this->record->paid_for())
                        ->required(),
                ])
                ->action(function (array $data) {
                    // if(Account::find($data['account_id'])->balance < $data['amount']){
                    //     Notification::make()
                    //         ->title('Недостатньо коштів на рахунку для оплати накладної.')
                    //         ->danger()
                    //         ->send();
                    //     return;
                    // }
                    Transaction::create([
                        'order_id' => $this->record->id,
                        'account_id' => $data['account_id'],
                        'transaction_type' => 'надходження',
                        'amount' => $data['amount'],
                        'transaction_date' => now(),
                        'description' => 'Оплата замовлення #' . $this->record->id.' - '. $this->record->customer->name,
                        'user_id' => $this->record->customer_id,
                        'manager_id' => auth()->id(),
                    ]);

                    if($this->record->paid_for() >= $this->record->total_amount)
                    {
                        $this->record->update(['status' => 'оплачено']);
                    }else if($this->record->paid_for() < $this->record->total_amount && $this->record->paid_for() > 0){
                        $this->record->update(['status' => 'частково оплачено']);
                    }

                    $this->record->payOrder();

                    Notification::make()
                        ->title('Оплата замовлення #' . $this->record->id . ' - ' . $this->record->customer->name . ' успішно проведена.')
                        ->success()
                        ->send();
                }),
        ];
    }

}
