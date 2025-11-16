<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use App\Models\Production;
//use App\Models\Warehouse;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class OrderCreate
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Ім\'я')
                            ->required(),
                        TextInput::make('email')
                            ->required()
                            ->label('Електронна пошта')
                            ->email(),
                    ])
                    ->relationship('customer', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Замовник'),
                // TextInput::make('total_amount')
                //     ->required()
                //     ->default(0)
                //     ->label('Загальна сума')
                //     ->numeric(),
                // TextInput::make('discount_amount')
                //     ->required()
                //     ->label('Сума знижки')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('paid_amount')
                //     ->required()
                //     ->label('Сплачена сума')
                //     ->numeric()
                //     ->default(0),
                // TextInput::make('due_amount')
                //     ->required()
                //     ->label('Сума до сплати')
                //     ->numeric()
                //     ->default(0),
                DatePicker::make('deadline')
                    ->label('Виотовирити до'),
                Textarea::make('notes')
                    ->label('Примітки')
                    ->columnSpanFull(),
                // Select::make('status')
                //     ->required()
                //     ->options([
                //         'pending' => 'В очікуванні',
                //         'processing' => 'В процесі',
                //         'completed' => 'Завершено',
                //         'canceled' => 'Скасовано',
                //     ])
                //     ->label('Статус')
                //     ->default('pending'),
                Repeater::make('orderItems')
                    ->relationship()
                    ->dehydrated(true)
                    ->columnSpanFull()
                    ->label('Позиції замовлення')
                    ->createItemButtonLabel('Додати позицію')
                    ->schema([
                        // Select::make('warehouse')
                        //             ->label('Вибрати зі складу')
                        //             ->options(Warehouse::all()->pluck('name', 'id'))
                        //             ->searchable()
                        //             ->preload()
                        //             ->nullable(),
                        Select::make('product_id')
                            ->label('Продукт')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->preload()
                            ->dehydrated(true)
                            ->searchable()
                            ->nullable(),
                        // Select::make('production_id')
                        //     ->label('Виробництво')
                        //     ->preload()
                        //     ->options(Production::where('is_template', true)->pluck('name', 'id'))
                        //     ->searchable()
                        //     ->nullable(),
                        TextInput::make('quantity')
                            ->label('Кількість')
                            ->required()
                            ->dehydrated(true)
                            ->numeric()
                            ->default(1),
                        // TextInput::make('unit_price')
                        //     ->label('Ціна за одиницю')
                        //     ->required()
                        //     ->numeric()
                        //     ->default(0),
                        // TextInput::make('total')
                        //     ->label('Загальна сума')
                        //     ->required()
                        //     ->numeric()
                        //     ->default(0),
                        Toggle::make('create_production')
                            ->label('Замовити на виробництві')
                            ->dehydrated(true)
                            ->reactive(),
                        Fieldset::make('production_details')
                            ->label('Деталі виробництва')
                            ->visible(fn ($get) => $get('create_production'))
                            ->schema([
                                Select::make('production.template_id')
                                    ->dehydrated(true)
                                    ->label('Вибрати шаблон виробництва')
                                    ->options(Production::where('is_template', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $template = Production::find($state);
                                            if ($template) {
                                                $set('production.name', $template->name);
                                                $set('production.description', $template->description);
                                            }
                                        }
                                    }),

                                TextInput::make('production.name')
                                    ->label('Назва виробництва')
                                    ->dehydrated(true)
                                    ->required(),
                                Textarea::make('production.description')
                                    ->dehydrated(true)
                                    ->label('Опис')
                                    ->nullable(),


                                Repeater::make('production.materials')
                                    ->label('Матеріали для виробництва')
                                    ->createItemButtonLabel('Додати матеріал')
                                    ->schema([
                                        Select::make('material_id')
                                            ->label('Назва матеріалу')
                                            ->searchable()
                                            ->preload()
                                            ->options(\App\Models\Material::all()->pluck('name', 'id')->toArray())
                                            ->required(),
                                        // TextInput::make('quantity')
                                        //     ->label('Кількість')
                                        //     ->required()
                                        //     ->numeric()
                                        //     ->default(1),
                                    ]),
                                // TextInput::make('production.cost_price')
                                //     ->label('Собівартість')
                                //     ->required()
                                //     ->numeric()
                                //     ->default(0),

                            ]),
                    ]),
            ]);
    }
}
