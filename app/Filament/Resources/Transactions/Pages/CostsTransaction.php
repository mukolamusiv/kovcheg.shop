<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\Schemas\CostsForm;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Account;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;

class CostsTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected static ?string $title = 'Додати витрати';

    public function form(Schema $schema): Schema
    {
        return CostsForm::configure($schema);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['transaction_type'] = 'витрати';
        $data['manager_id'] = auth()->id();
        $data['user_id'] = $data['user_id'] ?? auth()->id();

        return $data;
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

    protected function getRedirectUrl(): string
    {
        return TransactionResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Витрату успішно додано';
    }
}
