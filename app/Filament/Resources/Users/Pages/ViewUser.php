<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\ManagerOrdersStatsWidget;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderWidgets(): array
    {
        if (! $this->getRecord()->isManagerRole()) {
            return [];
        }

        return [
            ManagerOrdersStatsWidget::make([
                'userId' => $this->getRecord()->id,
            ]),
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    protected function resolveRecord(int | string $key): Model
    {
        return parent::resolveRecord($key)->load([
            'customer',
            'orders',
            'managedOrders',
            'salaries',
            'expenses',
            'transactions.account',
            'managedTransactions.account',
            'supplierInvoices',
            'assignedStages.production',
        ]);
    }
}
