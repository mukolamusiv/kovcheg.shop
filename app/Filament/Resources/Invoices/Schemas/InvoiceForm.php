<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Material;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('invoice_number')
                //     ->label('Номер рахунку')
                //     ->required(),
                DatePicker::make('invoice_date')
                    ->label('Дата накладної')
                    ->default(now())
                    ->required(),
                // TextInput::make('total_amount')
                //     ->label('Загальна сума')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
                TextInput::make('status')
                    ->label('Статус')
                    ->required()
                    ->default('не оплачено'),
                Select::make('supplier_id')
                    ->label('Постачальник')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Ім\'я постачальника')
                            ->required(),
                        TextInput::make('email')
                            ->label('Електронна пошта')
                            ->email()
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'role' => 'supplier',
                            // 'password' => bcrypt('password123'), // мінімальний пароль
                        ]);

                        return $user->id; // повертає ID для select
                    })
                    ->options(fn () => User::where('role', 'supplier')->pluck('name', 'id')),

                Repeater::make('items')
                    ->columnSpanFull()
                    ->relationship('items')
                    ->label('Позиції накладної')
                    ->schema([
                        Select::make('material_id')
                            ->label('Матеріал')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->options(fn () => Material::pluck('name', 'id')),
                        TextInput::make('quantity')
                            ->label('Кількість')
                            ->required()
                            ->numeric()
                            ->default(1),
                        // TextInput::make('price_per_unit')
                        //     ->label('Ціна за одиницю (в копійках)')
                        //     ->required()
                        //     ->numeric()
                        //     ->default(0),
                        // TextInput::make('total_price')
                        //     ->label('Загальна ціна (в копійках)')
                        //     ->required()
                        //     ->numeric()
                        //     ->default(0),
                    ])
                    ->columns(2)
                    ->required(),
            ]);
    }
}
