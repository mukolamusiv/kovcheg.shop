<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Form;

class CostsTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;


    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('description')
    //                 ->label('Опис')
    //                 ->required(),
    //             Forms\Components\TextInput::make('amount')
    //                 ->label('Сума')
    //                 ->numeric()
    //                 ->required(),
    //             Forms\Components\DatePicker::make('date')
    //                 ->label('Дата')
    //                 ->required(),
    //         ]);
    // }
}
