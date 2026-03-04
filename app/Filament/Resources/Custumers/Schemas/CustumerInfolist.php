<?php

namespace App\Filament\Resources\Custumers\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class CustumerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Інформація про клієнта')
                    //->label('Інформація про клієнта')
                    ->columns(2)
                    ->headerActions([
                        Action::make('edit')
                            ->label('Редагувати')
                            ->form([
                                TextInput::make('name')
                                    ->label('Ім\'я')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Електронна пошта')
                                    ->unique(ignoreRecord: true)
                                    ->email(),
                                Select::make('role')
                                    ->label('Роль')
                                    ->options([
                                        'admin' => 'Адміністратор',
                                        'manager' => 'Менеджер',
                                        'employee' => 'Працівник',
                                        'supplier' => 'Постачальник',
                                        'customer' => 'Клієнт',
                                    ])
                                    ->default('customer')
                                    ->required(),
                            ])->fillForm(function ($record) {
                                return [
                                    'name' => $record->name,
                                    'email' => $record->email,
                                    'role' => $record->role,
                                ];
                            })
                            ->action(function ($record, $data) {
                                $record->update($data);
                                $record->save();
                                Notification::make()
                                    ->title('Інформація про клієнта оновлена')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->schema([
                        TextEntry::make('name')
                            ->label('Ім\'я'),
                        TextEntry::make('email')
                            ->label('Електронна пошта'),
                        TextEntry::make('role')
                            ->label('Роль')
                            ->badge(),
                        TextEntry::make('email_verified_at')
                            ->label('Електронна пошта підтверджена')
                            ->dateTime(),
                    ]),
                Section::make('Детальна інформація про клієнта')
                    ->headerActions([
                        Action::make('edit_details')
                            ->label('Редагувати деталі')
                            ->form([
                                TextInput::make('customer.phone')
                                    ->label('Телефон'),
                                TextInput::make('customer.address')
                                    ->label('Адреса'),
                                TextInput::make('customer.city')
                                    ->label('Місто'),
                                DatePicker::make('customer.date_of_birth')
                                    ->label('Дата народження'),
                                Textarea::make('customer.note')
                                    ->label('Примітка'),
                                KeyValue::make('measurements')
                                    ->label('Розміри'),
                            ])->fillForm(function ($record) {
                                $customer = $record->customer;
                                return [
                                    'customer' => [
                                        'phone' => $customer->phone ?? '',
                                        'address' => $customer->address ?? '',
                                        'city' => $customer->city ?? '',
                                        'date_of_birth' => $customer->date_of_birth ?? '',
                                        'note' => $customer->note ?? '',
                                        'measurements' => $customer->measurements ?? '',
                                    ],
                                ];
                            })
                            ->action(function ($record, $data) {
                               // dd($data, $record,$data['customer']);
                                $record->customer()->update($data['customer']);
                               // $record->customer()->save();
                                $record->save();
                                Notification::make()
                                    ->title('Детальна інформація про клієнта оновлена')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->columns(2)
                    ->schema([
                        TextEntry::make('customer.phone')
                            ->label('Телефон'),
                        TextEntry::make('customer.address')
                            ->label('Адреса'),
                        TextEntry::make('customer.city')
                            ->label('Місто'),
                        TextEntry::make('customer.date_of_birth')
                            ->label('Дата народження')
                            ->date(),
                        TextEntry::make('customer.note')
                            ->label('Примітка'),
                        KeyValue::make('measurements')
                            ->columnSpanFull()
                            ->label('Розміри'),
                    ]),
            ]);
    }
}
