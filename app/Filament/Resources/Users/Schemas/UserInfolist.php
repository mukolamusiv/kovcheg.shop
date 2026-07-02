<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Expenses\ExpenseResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Productions\ProductionResource;
use App\Filament\Resources\Salaries\SalaryResource;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Expense;
use App\Models\User;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Профіль користувача')
                            ->icon(Heroicon::UserCircle)
                            ->columnSpan([
                                'default' => 1,
                                'lg' => 2,
                            ])
                            ->columns(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Ім\'я'),
                                TextEntry::make('email')
                                    ->label('Електронна пошта')
                                    ->copyable()
                                    ->placeholder('—'),
                                TextEntry::make('role')
                                    ->label('Роль')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => User::roleLabels()[$state] ?? $state)
                                    ->color(fn (string $state): string => match ($state) {
                                        'admin' => 'danger',
                                        'manager' => 'warning',
                                        'employee' => 'info',
                                        'supplier' => 'success',
                                        'customer' => 'primary',
                                        default => 'gray',
                                    }),
                                TextEntry::make('email_verified_at')
                                    ->label('Пошту підтверджено')
                                    ->dateTime('d.m.Y H:i')
                                    ->placeholder('Ні'),
                                TextEntry::make('created_at')
                                    ->label('Зареєстровано')
                                    ->dateTime('d.m.Y H:i'),
                                TextEntry::make('updated_at')
                                    ->label('Оновлено')
                                    ->dateTime('d.m.Y H:i'),
                            ]),

                        Section::make('Підсумки')
                            ->icon(Heroicon::ChartBar)
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('orders_count')
                                    ->label('Замовлень')
                                    ->state(fn (User $record): int => $record->orders->count()),
                                TextEntry::make('orders_total')
                                    ->label('Сума замовлень')
                                    ->money('UAH')
                                    ->state(fn (User $record): float => $record->orders->sum('total_amount')),
                                TextEntry::make('salaries_total')
                                    ->label('Зарплати')
                                    ->money('UAH')
                                    ->state(fn (User $record): float => $record->salaries->sum('amount'))
                                    ->visible(fn (User $record): bool => $record->salaries->isNotEmpty()),
                                TextEntry::make('expenses_total')
                                    ->label('Витрати')
                                    ->money('UAH')
                                    ->state(fn (User $record): float => $record->expenses->sum('amount'))
                                    ->visible(fn (User $record): bool => $record->expenses->isNotEmpty()),
                                TextEntry::make('transactions_income')
                                    ->label('Надходження')
                                    ->money('UAH')
                                    ->color('success')
                                    ->state(fn (User $record): float => $record->transactions
                                        ->where('transaction_type', 'надходження')
                                        ->sum('amount'))
                                    ->visible(fn (User $record): bool => $record->transactions
                                        ->where('transaction_type', 'надходження')
                                        ->isNotEmpty()),
                                TextEntry::make('transactions_expense')
                                    ->label('Списання')
                                    ->money('UAH')
                                    ->color('danger')
                                    ->state(fn (User $record): float => $record->transactions
                                        ->where('transaction_type', 'витрати')
                                        ->sum('amount'))
                                    ->visible(fn (User $record): bool => $record->transactions
                                        ->where('transaction_type', 'витрати')
                                        ->isNotEmpty()),
                                TextEntry::make('stages_count')
                                    ->label('Етапів виробництва')
                                    ->state(fn (User $record): int => $record->assignedStages->count())
                                    ->visible(fn (User $record): bool => $record->assignedStages->isNotEmpty()),
                            ]),
                    ]),

                Section::make('Профіль клієнта')
                    ->icon(Heroicon::Identification)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->customer !== null)
                    ->columns(3)
                    ->schema([
                        TextEntry::make('customer.phone')
                            ->label('Телефон')
                            ->copyable()
                            ->placeholder('—'),
                        TextEntry::make('customer.city')
                            ->label('Місто')
                            ->placeholder('—'),
                        TextEntry::make('customer.date_of_birth')
                            ->label('Дата народження')
                            ->date('d.m.Y')
                            ->placeholder('—'),
                        TextEntry::make('customer.address')
                            ->label('Адреса')
                            ->columnSpan(2)
                            ->placeholder('—'),
                        TextEntry::make('customer.note')
                            ->label('Примітка')
                            ->columnSpanFull()
                            ->placeholder('—'),
                        TextEntry::make('customer.measurements')
                            ->label('Розміри')
                            ->columnSpanFull()
                            ->formatStateUsing(function ($state): string {
                                if (blank($state) || ! is_array($state)) {
                                    return '—';
                                }

                                return collect($state)
                                    ->map(fn ($value, $key) => "{$key}: {$value}")
                                    ->implode(', ');
                            }),
                    ]),

                Section::make('Замовлення')
                    ->icon(Heroicon::ShoppingBag)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->orders->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('orders')
                            ->label('')
                            ->table([
                                TableColumn::make('№'),
                                TableColumn::make('Сума'),
                                TableColumn::make('Оплачено'),
                                TableColumn::make('До сплати'),
                                TableColumn::make('Статус'),
                                TableColumn::make('Дедлайн'),
                            ])
                            ->schema([
                                TextEntry::make('id')
                                    ->label('№')
                                    ->url(fn ($record) => OrderResource::getUrl('view', ['record' => $record])),
                                TextEntry::make('total_amount')
                                    ->label('Сума')
                                    ->money('UAH'),
                                TextEntry::make('paid_amount')
                                    ->label('Оплачено')
                                    ->money('UAH'),
                                TextEntry::make('due_amount')
                                    ->label('До сплати')
                                    ->money('UAH'),
                                TextEntry::make('status')
                                    ->label('Статус')
                                    ->badge(),
                                TextEntry::make('deadline')
                                    ->label('Дедлайн')
                                    ->date('d.m.Y')
                                    ->placeholder('—'),
                            ]),
                    ]),

                Section::make('Накладні постачальника')
                    ->icon(Heroicon::DocumentText)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->supplierInvoices->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('supplierInvoices')
                            ->label('')
                            ->table([
                                TableColumn::make('Номер'),
                                TableColumn::make('Дата'),
                                TableColumn::make('Сума'),
                                TableColumn::make('Статус'),
                            ])
                            ->schema([
                                TextEntry::make('invoice_number')
                                    ->label('Номер')
                                    ->url(fn ($record) => InvoiceResource::getUrl('view', ['record' => $record])),
                                TextEntry::make('invoice_date')
                                    ->label('Дата')
                                    ->date('d.m.Y'),
                                TextEntry::make('total_amount')
                                    ->label('Сума')
                                    ->money('UAH'),
                                TextEntry::make('status')
                                    ->label('Статус')
                                    ->badge(),
                            ]),
                    ]),

                Section::make('Зарплати')
                    ->icon(Heroicon::Banknotes)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->salaries->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('salaries')
                            ->label('')
                            ->table([
                                TableColumn::make('Дата'),
                                TableColumn::make('Сума'),
                                TableColumn::make('Статус'),
                                TableColumn::make('Примітки'),
                            ])
                            ->schema([
                                TextEntry::make('salary_date')
                                    ->label('Дата')
                                    ->date('d.m.Y')
                                    ->url(fn ($record) => SalaryResource::getUrl('view', ['record' => $record])),
                                TextEntry::make('amount')
                                    ->label('Сума')
                                    ->money('UAH'),
                                TextEntry::make('status')
                                    ->label('Статус')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'виплачено' => 'success',
                                        'нараховано' => 'warning',
                                        default => 'danger',
                                    }),
                                TextEntry::make('notes')
                                    ->label('Примітки')
                                    ->limit(40)
                                    ->placeholder('—'),
                            ]),
                    ]),

                Section::make('Витрати')
                    ->icon(Heroicon::ReceiptPercent)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->expenses->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('expenses')
                            ->label('')
                            ->table([
                                TableColumn::make('Категорія'),
                                TableColumn::make('Опис'),
                                TableColumn::make('Сума'),
                                TableColumn::make('Дата'),
                                TableColumn::make('Статус'),
                            ])
                            ->schema([
                                TextEntry::make('category')
                                    ->label('Категорія')
                                    ->formatStateUsing(fn (string $state): string => Expense::CATEGORIES[$state] ?? $state)
                                    ->url(fn ($record) => ExpenseResource::getUrl('view', ['record' => $record])),
                                TextEntry::make('description')
                                    ->label('Опис')
                                    ->limit(40),
                                TextEntry::make('amount')
                                    ->label('Сума')
                                    ->money('UAH'),
                                TextEntry::make('expense_date')
                                    ->label('Дата')
                                    ->date('d.m.Y'),
                                TextEntry::make('status')
                                    ->label('Статус')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'проведено' => 'success',
                                        'заплановано' => 'warning',
                                        default => 'danger',
                                    }),
                            ]),
                    ]),

                Section::make('Транзакції (учасник)')
                    ->icon(Heroicon::ArrowsRightLeft)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->transactions->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('transactions')
                            ->label('')
                            ->table([
                                TableColumn::make('Дата'),
                                TableColumn::make('Тип'),
                                TableColumn::make('Сума'),
                                TableColumn::make('Рахунок'),
                                TableColumn::make('Опис'),
                            ])
                            ->schema([
                                TextEntry::make('transaction_date')
                                    ->label('Дата')
                                    ->dateTime('d.m.Y H:i')
                                    ->url(fn ($record) => TransactionResource::getUrl('view', ['record' => $record])),
                                TextEntry::make('transaction_type')
                                    ->label('Тип')
                                    ->badge()
                                    ->color(fn (string $state): string => $state === 'надходження' ? 'success' : 'danger'),
                                TextEntry::make('amount')
                                    ->label('Сума')
                                    ->money('UAH'),
                                TextEntry::make('account.account_name')
                                    ->label('Рахунок')
                                    ->placeholder('—'),
                                TextEntry::make('description')
                                    ->label('Опис')
                                    ->limit(50)
                                    ->placeholder('—'),
                            ]),
                    ]),

                Section::make('Проведені операції (менеджер)')
                    ->icon(Heroicon::ClipboardDocumentCheck)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->managedTransactions->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('managedTransactions')
                            ->label('')
                            ->table([
                                TableColumn::make('Дата'),
                                TableColumn::make('Тип'),
                                TableColumn::make('Сума'),
                                TableColumn::make('Рахунок'),
                                TableColumn::make('Опис'),
                            ])
                            ->schema([
                                TextEntry::make('transaction_date')
                                    ->label('Дата')
                                    ->dateTime('d.m.Y H:i')
                                    ->url(fn ($record) => TransactionResource::getUrl('view', ['record' => $record])),
                                TextEntry::make('transaction_type')
                                    ->label('Тип')
                                    ->badge()
                                    ->color(fn (string $state): string => $state === 'надходження' ? 'success' : 'danger'),
                                TextEntry::make('amount')
                                    ->label('Сума')
                                    ->money('UAH'),
                                TextEntry::make('account.account_name')
                                    ->label('Рахунок')
                                    ->placeholder('—'),
                                TextEntry::make('description')
                                    ->label('Опис')
                                    ->limit(50)
                                    ->placeholder('—'),
                            ]),
                    ]),

                Section::make('Етапи виробництва')
                    ->icon(Heroicon::WrenchScrewdriver)
                    ->columnSpanFull()
                    ->visible(fn (User $record): bool => $record->assignedStages->isNotEmpty())
                    ->schema([
                        RepeatableEntry::make('assignedStages')
                            ->label('')
                            ->table([
                                TableColumn::make('Виробництво'),
                                TableColumn::make('Етап'),
                                TableColumn::make('Вартість'),
                                TableColumn::make('Статус'),
                                TableColumn::make('Тривалість'),
                            ])
                            ->schema([
                                TextEntry::make('production.name')
                                    ->label('Виробництво')
                                    ->placeholder('—')
                                    ->url(fn ($record) => $record->production
                                        ? ProductionResource::getUrl('view', ['record' => $record->production])
                                        : null),
                                TextEntry::make('name')
                                    ->label('Етап'),
                                TextEntry::make('cost')
                                    ->label('Вартість')
                                    ->money('UAH'),
                                TextEntry::make('status')
                                    ->label('Статус')
                                    ->badge(),
                                TextEntry::make('duration')
                                    ->label('Тривалість')
                                    ->suffix(' хв')
                                    ->placeholder('—'),
                            ]),
                    ]),
            ]);
    }
}
