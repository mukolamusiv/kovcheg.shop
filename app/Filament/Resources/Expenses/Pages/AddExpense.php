<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Resources\Expenses\ExpenseResource;
use App\Filament\Resources\Expenses\Schemas\AddExpenseForm;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class AddExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected static ?string $title = 'Додати витрату';

    public function form(Schema $schema): Schema
    {
        return AddExpenseForm::configure($schema);
    }

    protected function beforeCreate(): void
    {
        $account = Account::find($this->data['account_id'] ?? null);

        if ($account && $account->balance < ($this->data['amount'] ?? 0)) {
            Notification::make()
                ->title('Недостатньо коштів на рахунку для проведення витрати.')
                ->danger()
                ->send();

            throw new Halt();
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
        $expense = Expense::create([
            'category' => $data['category'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'expense_date' => $data['expense_date'],
            'notes' => $data['notes'] ?? null,
            'status' => 'проведено',
            'user_id' => $data['user_id'] ?? auth()->id(),
        ]);

        Transaction::create([
            'account_id' => $data['account_id'],
            'transaction_type' => 'витрати',
            'amount' => $data['amount'],
            'description' => $expense->categoryLabel . ': ' . $expense->description,
            'user_id' => $expense->user_id,
            'manager_id' => auth()->id(),
            'transaction_date' => $data['expense_date'],
        ]);

        return $expense;
    }

    protected function getRedirectUrl(): string
    {
        return ExpenseResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Витрату успішно додано';
    }
}
