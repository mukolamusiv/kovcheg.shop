<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Pages\TreeCategories;
use App\Filament\Resources\Categories\Pages\ViewCategory;
use App\Filament\Resources\Categories\RelationManagers\MaterialsRelationManager;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Schemas\CategoryInfolist;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;


use Openplain\FilamentTreeView\Fields\IconField;
use Openplain\FilamentTreeView\Fields\TextField;
use Openplain\FilamentTreeView\Tree;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Folder;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Матеріали';

    protected static ?string $navigationLabel = 'Категорії';

    protected static ?string $pluralModelLabel = 'Категорії';

    protected static ?string $modelLabel = 'Категорія';


    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MaterialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'all' => TreeCategories::route('/all'),
            'create' => CreateCategory::route('/create'),
            'view' => ViewCategory::route('/{record}'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }


    public static function tree(Tree $tree): Tree
    {
        return $tree
            ->fields([
                TextField::make('name'),
                TextField::make('description'),
                //TextField::make('parent_id'),
                TextField::make('type'),
                //IconField::make('is_active'),
            ])
            ->recordActions([

                ViewAction::make()
                    ->label('Переглянути')
                    ->url(fn (Category $record): string =>
                        static::getUrl('view', ['record' => $record])
                    ),

                Action::make('editModal')
                    ->label('Швидке редагування')
                    ->icon('heroicon-o-pencil-square')

                    ->fillForm(fn (Category $record): array => [
                        'name' => $record->name,
                        'description' => $record->description,
                        'type' => $record->type,
                    ])

                    ->form([
                        TextInput::make('name')
                            ->label('Назва')
                            ->required(),

                        Textarea::make('description')
                            ->label('Опис'),

                        Select::make('type')
                            ->label('Тип')
                            ->options([
                                'product' => 'Продукт',
                                'material' => 'Матеріал',
                            ])
                            ->default('material'),
                    ])

                    ->action(function (Category $record, array $data) {

                        $record->update($data);

                        Notification::make()
                            ->title('Категорію оновлено')
                            ->success()
                            ->send();

                    }),

                DeleteAction::make()
                    ->label('Видалити')

                    ->modalDescription(function (Category $record): string {

                        $count = $record->descendants()->count();

                        if ($count === 0) {
                            return 'Видалити категорію?';
                        }

                        return "Буде видалено також {$count} підкатегорій.";

                    }),

            ]);
    }


    // protected function getTableQuery(): Builder
    // {
    //     return Category::query()->with('parent');
    // }

    // protected function getTableQuery(): Builder
    // {
    //     return Category::query()
    //         ->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
    //         ->select('categories.*', 'parents.name as parent_name');
    // }
}
