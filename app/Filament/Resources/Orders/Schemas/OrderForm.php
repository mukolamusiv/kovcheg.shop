<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use PhpParser\Node\Stmt\Label;

class OrderForm
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
                    ->label('Замовник'),
                TextInput::make('total_amount')
                    ->required()
                    ->default(0)
                    ->label('Загальна сума')
                    ->numeric(),
                TextInput::make('discount_amount')
                    ->required()
                    ->label('Сума знижки')
                    ->numeric()
                    ->default(0),
                TextInput::make('paid_amount')
                    ->required()
                    ->label('Сплачена сума')
                    ->numeric()
                    ->default(0),
                TextInput::make('due_amount')
                    ->required()
                    ->label('Сума до сплати')
                    ->numeric()
                    ->default(0),
                DatePicker::make('deadline'),
                Textarea::make('notes')
                    ->label('Примітки')
                    ->columnSpanFull(),
                Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'В очікуванні',
                        'processing' => 'В процесі',
                        'completed' => 'Завершено',
                        'canceled' => 'Скасовано',
                    ])
                    ->label('Статус')
                    ->default('pending'),
            ]);
    }
}
