<?php

namespace App\Filament\Resources\Suppliners\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplinerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Інформація про постачальника')
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
                                    ->default('supplier')
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
                                    ->title('Інформація про постачальника оновлена')
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
                Section::make('Детальна інформація про постачальника')
                    ->headerActions([
                        Action::make('edit_details')
                            ->label('Редагувати деталі')
                            ->form([
                                TextInput::make('supplier.phone')
                                    ->label('Телефон'),
                                TextInput::make('supplier.company_name')
                                    ->label('Назва компанії'),
                                TextInput::make('supplier.address')
                                    ->label('Адреса'),
                                TextInput::make('supplier.city')
                                    ->label('Місто'),
                                Textarea::make('supplier.note')
                                    ->label('Примітка'),
                            ])->fillForm(function ($record) {
                                $supplier = $record->supplier;
                                return [
                                    'supplier' => [
                                        'phone' => $supplier->phone ?? '',
                                        'company_name' => $supplier->company_name ?? '',
                                        'address' => $supplier->address ?? '',
                                        'city' => $supplier->city ?? '',
                                        'note' => $supplier->note ?? '',
                                    ],
                                ];
                            })
                            ->action(function ($record, $data) {
                                $record->supplier()->update($data['supplier']);
                                $record->save();
                                Notification::make()
                                    ->title('Детальна інформація про постачальника оновлена')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->columns(2)
                    ->schema([
                        TextEntry::make('supplier.phone')
                            ->label('Телефон'),
                        TextEntry::make('supplier.company_name')
                            ->label('Назва компанії'),
                        TextEntry::make('supplier.address')
                            ->label('Адреса'),
                        TextEntry::make('supplier.city')
                            ->label('Місто'),
                        Textarea::make('supplier.note')
                            ->label('Примітка'),
                    ]),
            ]);
    }
}
