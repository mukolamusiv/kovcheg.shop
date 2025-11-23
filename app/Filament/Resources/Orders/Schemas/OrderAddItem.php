<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use App\Models\Production;
use App\Models\Material;
use Filament\Schemas\Components\Section;
// use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;

class OrderAddItem
{
    public static function form(): array
    {
        return [
            Select::make('product_id')
                ->label('Продукт')
                ->options(Product::query()->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $product = Product::find($state);
                    $set('unit_price', $product?->price ?? 0);
                }),

            TextInput::make('quantity')
                ->label('Кількість')
                ->numeric()
                ->default(1)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    $set('total', ($get('unit_price') ?? 0) * $state);
                }),

            TextInput::make('unit_price')
                ->label('Ціна за одиницю')
                ->numeric()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, $get) {
                    $set('total', ($get('quantity') ?? 0) * $state);
                }),

            TextInput::make('total')
                ->label('Загальна сума')
                ->numeric()
                ->disabled(),

            Toggle::make('create_production')
                ->label('Замовити на виробництві')
                ->reactive(),

            Section::make('Деталі виробництва')
                ->visible(fn ($get) => (bool) $get('create_production'))
                ->schema([

                    Select::make('production.template_id')
                        ->label('Шаблон виробництва')
                        ->options(
                            Production::query()
                                ->where('is_template', true)
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->nullable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state && ($template = Production::find($state))) {
                                $set('production.name', $template->name);
                                $set('production.description', $template->description);
                            }
                        }),

                    TextInput::make('production.name')
                        ->label('Назва виробництва')
                        ->required(),

                    Textarea::make('production.description')
                        ->label('Опис')
                        ->nullable(),

                    Builder::make('production.materials')
                        ->label('Матеріали')
                        ->blocks([
                            Block::make('material')
                                ->label('Матеріал')
                                ->schema([

                                    Select::make('material_id')
                                        ->label('Матеріал')
                                        ->options(
                                            Material::query()->pluck('name', 'id')
                                        )
                                        ->searchable()
                                        ->required(),

                                    TextInput::make('quantity')
                                        ->label('Кількість')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),

                                ]),
                        ]),
                ]),
        ];
    }
}
