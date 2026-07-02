<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use App\Models\User;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Замовник')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус замовлення')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                            'створено' => 'gray',
                            'оформлено' => 'info',
                            'виготовляється' => 'warning',
                            'виготовлено' => 'secondary',
                            'готове' => 'success',
                            'скасовано' => 'danger',
                            default => 'info',
                        })
                    ->searchable(),
                // TextColumn::make('total_amount')
                //     ->numeric()
                //     ->label('Загальна сума')
                //     ->sortable(),
                // TextColumn::make('discount_amount')
                //     ->numeric()
                //     ->label('Сума знижки')
                //     ->sortable(),
                // TextColumn::make('paid_amount')
                //     ->numeric()
                //     ->label('Сплачена сума')
                //     ->sortable(),
                TextColumn::make('due_amount')
                    ->numeric()
                    ->label('Сума до сплати')
                    ->sortable(),

                TextColumn::make('shipping_method')
                    ->label('Спосіб доставки')
                    ->default('Самовивіз')
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('statusPaymentss')
                    ->label('Статус оплати')
                    //->sortable()
                    ->color(fn ($record) => match ($record->statusPayment()) {
                            'оплачено' => 'success',
                            'частково оплачено' => 'warning',
                            'не сплачено' => 'danger',
                            default => 'info',
                        })
                    ->default(fn ($record) => $record->statusPayment()),
                TextColumn::make('deadline')
                    ->date()
                    ->label('Термін виконання')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Примітки')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([10, 25, 50, 100, 'all'])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\Filter::make('created_at')
                    ->label('Дата створення')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('Від'),
                        \Filament\Forms\Components\DatePicker::make('to')
                            ->label('До'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        return $query
                            ->when($data['from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['to'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $from = $data['from'] ?? null;
                        $to = $data['to'] ?? null;

                        if ($from && $to) {
                            return "З {$from} по {$to}";
                        }

                        if ($from) {
                            return "З {$from}";
                        }

                        if ($to) {
                            return "До {$to}";
                        }

                        return null;
                    }),
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->bulkActions([
                static::assignManagerBulkAction(),
                ExportBulkAction::make('export')
                    ->label('Експорт до Excel'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    static::assignManagerBulkAction(),
                    ExportBulkAction::make('export')
                        ->label('Експорт до Excel'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function assignManagerBulkAction(): BulkAction
    {
        return BulkAction::make('assignManager')
            ->label('Призначити менеджера')
            ->icon('heroicon-o-user-plus')
            ->color('primary')
            ->form([
                Select::make('manager_id')
                    ->label('Менеджер')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn () => User::whereIn('role', ['manager', 'admin'])
                        ->orderBy('name')
                        ->pluck('name', 'id')),
            ])
            ->action(function (Collection $records, array $data): void {
                $records->each(fn (Order $order) => $order->update([
                    'manager_id' => $data['manager_id'],
                ]));

                Notification::make()
                    ->title('Менеджера призначено')
                    ->body('Оновлено замовлень: ' . $records->count())
                    ->success()
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }
}
