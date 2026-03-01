<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SliderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Заголовок'),
                TextEntry::make('subtitle')
                    ->label('Подзаголовок'),
                TextEntry::make('link')
                    ->label('Посилання'),
                 TextEntry::make('is_active')
                    ->label('Активний')
                    ->formatStateUsing(fn ($state) => $state ? 'Так' : 'Ні'),
                 ImageEntry::make('image_path')
                    ->label('Зображення')
                    //->directory('sliders'),
            ]);
    }
}
