<?php

namespace App\Filament\Resources\Custumers;

use App\Filament\Resources\Custumers\Pages\CreateCustumer;
use App\Filament\Resources\Custumers\Pages\EditCustumer;
use App\Filament\Resources\Custumers\Pages\ListCustumers;
use App\Filament\Resources\Custumers\Pages\ViewCustumer;
use App\Filament\Resources\Custumers\Schemas\CustumerForm;
use App\Filament\Resources\Custumers\Schemas\CustumerInfolist;
use App\Filament\Resources\Custumers\Tables\CustumersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustumerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Люди';

    protected static ?string $navigationLabel = 'Список клієнтів';

    protected static ?string $pluralModelLabel = 'Клієнти';

    public static function form(Schema $schema): Schema
    {
        return CustumerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustumerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustumersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustumers::route('/'),
            'create' => CreateCustumer::route('/create'),
            'view' => ViewCustumer::route('/{record}'),
            'edit' => EditCustumer::route('/{record}/edit'),
        ];
    }
}
