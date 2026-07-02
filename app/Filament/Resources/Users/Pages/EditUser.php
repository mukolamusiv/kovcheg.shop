<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('changePassword')
                ->label('Змінити пароль')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->visible(fn (): bool => auth()->user()?->role === 'admin')
                ->form([
                    TextInput::make('password')
                        ->label('Новий пароль')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->label('Підтвердження пароля')
                        ->password()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'password' => $data['password'],
                    ]);

                    Notification::make()
                        ->title('Пароль успішно змінено')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['password'], $data['password_confirmation']);

        return $data;
    }
}
