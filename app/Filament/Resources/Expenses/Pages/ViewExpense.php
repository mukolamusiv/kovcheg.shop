<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use App\Models\Account;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewExpense extends ViewRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('conduct_expense')
                ->label('Провести витрату')
                ->button()
                ->color('success')
                ->hidden(fn () => $this->record->status === 'проведено' || $this->record->status === 'скасовано')
                ->form([
                    Select::make('account_id')
                        ->label('Рахунок')
                        ->options(Account::pluck('account_name', 'id'))
                        ->required(),
                    TextInput::make('amount')
                        ->label('Сума')
                        ->numeric()
                        ->default(fn () => $this->record->amount)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $account = Account::find($data['account_id']);

                    if ($account->balance < $data['amount']) {
                        Notification::make()
                            ->title('Недостатньо коштів на рахунку для проведення витрати.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Transaction::create([
                        'account_id' => $data['account_id'],
                        'transaction_type' => 'витрати',
                        'amount' => $data['amount'],
                        'description' => $this->record->categoryLabel . ': ' . $this->record->description,
                        'user_id' => $this->record->user_id ?? auth()->id(),
                        'manager_id' => auth()->id(),
                        'transaction_date' => now(),
                    ]);

                    $this->record->update(['status' => 'проведено']);

                    Notification::make()
                        ->title('Витрату успішно проведено.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
