<?php

namespace App\Filament\Resources\Productions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Назва')
                    ->required(),
                Textarea::make('description')
                    ->label('Опис')
                    ->columnSpanFull(),
                TextInput::make('cost_price')
                    ->label('Собівартість')
                    ->required()
                    ->hidden()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_cost')
                    ->label('Загальна вартість')
                    ->required()
                    ->hidden()
                    ->numeric()
                    ->default(0),
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->label('ID замовлення')
                    ->preload()
                    ->getSearchResultsUsing(function (string $query) {
                        return \App\Models\Order::query()
                            ->where('id', 'like', "%{$query}%")
                            ->orWhereHas('customer', function ($queryBuilder) use ($query) {
                                $queryBuilder->where('name', 'like', "%{$query}%");
                            })
                            ->get()
                            ->mapWithKeys(function ($order) {
                                return [$order->id => "{$order->id} - {$order->customer->name}"];
                            });
                    })
                    ->searchable(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Продукт'),
                Toggle::make('is_template')
                    ->label('Шаблон')
                    ->required(),
                Select::make('status')
                    ->label('Статус')
                    ->required()
                    ->options([
                        'pending' => 'В очікуванні',
                        'in_progress' => 'В процесі',
                        'completed' => 'Завершено',
                        'canceled' => 'Скасовано',
                    ])
                    ->default('pending'),
            ]);
    }
}
