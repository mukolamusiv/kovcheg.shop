<?php

namespace App\Filament\Resources\Salaries\Pages;

use App\Filament\Resources\Salaries\SalaryResource;
use App\Filament\Resources\Salaries\Schemas\PaySalaryForm;
use App\Models\Account;
use App\Models\Salary;
use App\Models\Transaction;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;

class PaySalary extends CreateRecord
{
    protected static string $resource = SalaryResource::class;

    protected static ?string $title = 'Виплатити зарплату';

    public function form(Schema $schema): Schema
    {
        return PaySalaryForm::configure($schema);
    }

    protected function beforeCreate(): void
    {
        $account = Account::find($this->data['account_id'] ?? null);

        if ($account && $account->balance < ($this->data['amount'] ?? 0)) {
            Notification::make()
                ->title('Недостатньо коштів на рахунку для виплати зарплати.')
                ->danger()
                ->send();

            throw new Halt();
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
        $employee = User::findOrFail($data['user_id']);

        $salary = Salary::create([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'salary_date' => $data['salary_date'],
            'notes' => $data['notes'] ?? null,
            'status' => 'виплачено',
        ]);

        Transaction::create([
            'account_id' => $data['account_id'],
            'transaction_type' => 'витрати',
            'amount' => $data['amount'],
            'description' => 'Зарплата: ' . $employee->name,
            'user_id' => $data['user_id'],
            'manager_id' => auth()->id(),
            'transaction_date' => $data['salary_date'],
        ]);

        return $salary;
    }

    protected function getRedirectUrl(): string
    {
        return SalaryResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Зарплату успішно виплачено';
    }
}
