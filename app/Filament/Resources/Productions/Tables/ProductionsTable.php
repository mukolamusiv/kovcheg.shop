<?php

namespace App\Filament\Resources\Productions\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')
                    ->label('Клієнт')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),

                TextColumn::make('cost_price')
                    ->label('Собівартість')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Загальна вартість')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_template')
                    ->label('Шаблон')
                    ->boolean(),
                TextColumn::make('order_id')
                    ->label('ID замовлення')
                    ->numeric()
                    //->url(fn ($record) => route('filament.administration.resources.orders.edit', $record->order_id))
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Продукт')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->searchable(),

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

            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100, 'all'])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
