<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required(),
                TextInput::make('subtitle')
                    ->label('Подзаголовок')
                    ->required(),
                FileUpload::make('image_path')
                    ->label('Зображення')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions([
                        '16:9',
                    ])
                    ->directory('sliders')
                    ->required(),
                TextInput::make('link')
                    ->label('Посилання')
                    ->url()
                    ->required(),
                 Checkbox::make('is_active')
                    ->label('Активний')
                    ->default(true),
            ]);
    }
}
