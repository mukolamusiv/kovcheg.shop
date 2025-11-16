<?php

namespace App\Filament\Resources\Productions;

use App\Filament\Resources\Productions\Pages\CreateProduction;
use App\Filament\Resources\Productions\Pages\EditProduction;
use App\Filament\Resources\Productions\Pages\ListProductions;
use App\Filament\Resources\Productions\Pages\ViewProduction;
use App\Filament\Resources\Productions\RelationManagers\MaterialsRelationManager;
use App\Filament\Resources\Productions\RelationManagers\StagesRelationManager;
use App\Filament\Resources\Productions\Schemas\ProductionForm;
use App\Filament\Resources\Productions\Schemas\ProductionInfolist;
use App\Filament\Resources\Productions\Tables\ProductionsTable;
use App\Models\Production;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static ?string $recordTitleAttribute = 'name';


    protected static string | UnitEnum | null $navigationGroup = 'Замовлення';

    protected static ?string $navigationLabel = 'Виробництва';

    protected static ?string $pluralModelLabel = 'Виробництва';

        public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '!=', 'готово')->count();
    }


    public static function form(Schema $schema): Schema
    {
        return ProductionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProductionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StagesRelationManager::class,
            MaterialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductions::route('/'),
            'create' => CreateProduction::route('/create'),
            'view' => ViewProduction::route('/{record}'),
            'edit' => EditProduction::route('/{record}/edit'),
        ];
    }
}
