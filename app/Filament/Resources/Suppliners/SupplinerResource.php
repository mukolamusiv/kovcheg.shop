<?php

namespace App\Filament\Resources\Suppliners;

use App\Filament\Resources\Suppliners\Pages\CreateSuppliner;
use App\Filament\Resources\Suppliners\Pages\EditSuppliner;
use App\Filament\Resources\Suppliners\Pages\ListSuppliners;
use App\Filament\Resources\Suppliners\Pages\ViewSuppliner;
use App\Filament\Resources\Suppliners\Schemas\SupplinerForm;
use App\Filament\Resources\Suppliners\Schemas\SupplinerInfolist;
use App\Filament\Resources\Suppliners\Tables\SupplinersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SupplinerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup = 'Люди';

    protected static ?string $navigationLabel = 'Список постачальників';

    protected static ?string $pluralModelLabel = 'Постачальники';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('role', 'supplier');
    }

    public static function form(Schema $schema): Schema
    {
        return SupplinerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplinerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplinersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSuppliners::route('/'),
            'create' => CreateSuppliner::route('/create'),
            'view' => ViewSuppliner::route('/{record}'),
            'edit' => EditSuppliner::route('/{record}/edit'),
        ];
    }
}
