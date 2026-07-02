<?php

namespace App\Filament\Resources\Salaries\Pages;

use App\Filament\Resources\Salaries\SalaryResource;
use App\Models\Account;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewSalary extends ViewRecord
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('pay_salary')
                ->label('Виплатити зарплату')
                ->button()
                ->color('success')
                ->hidden(fn () => $this->record->status === 'виплачено')
                ->form([
                    Select::make('account_id')
                        ->label('Рахунок')
                        ->options(Account::pluck('account_name', 'id'))
                        ->required(),
                    TextInput::make('amount')
                        ->label('Сума виплати')
                        ->numeric()
                        ->default(fn () => $this->record->amount)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $account = Account::find($data['account_id']);

                    if ($account->balance < $data['amount']) {
                        Notification::make()
                            ->title('Недостатньо коштів на рахунку для виплати зарплати.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Transaction::create([
                        'account_id' => $data['account_id'],
                        'transaction_type' => 'витрати',
                        'amount' => $data['amount'],
                        'description' => 'Зарплата: ' . $this->record->user->name,
                        'user_id' => $this->record->user_id,
                        'manager_id' => auth()->id(),
                        'transaction_date' => now(),
                    ]);

                    $this->record->update(['status' => 'виплачено']);

                    Notification::make()
                        ->title('Зарплату успішно виплачено.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
