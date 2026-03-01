<?php

namespace App\Filament\Resources\Orders\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Замовник')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус замовлення')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                            'створено' => 'gray',
                            'оформлено' => 'info',
                            'виготовляється' => 'warning',
                            'виготовлено' => 'success',
                            'завершено' => 'secondary',
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
                TextColumn::make('deadline')
                    ->date()
                    ->label('Термін виконання')
                    ->sortable(),
                TextColumn::make('shipping_method')
                    ->label('Спосіб доставки')
                    ->default('Самовивіз')
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('statusPaymentss')
                    ->label('Статус оплати')
                    ->sortable()
                    ->color(fn ($record) => match ($record->statusPayment()) {
                            'оплачено' => 'success',
                            'частково оплачено' => 'warning',
                            'не сплачено' => 'danger',
                            default => 'info',
                        })
                    ->default(fn ($record) => $record->statusPayment()),
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
                ExportBulkAction::make('export')
                    ->label('Експорт до Excel'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make('export')
                        ->label('Експорт до Excel'),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
