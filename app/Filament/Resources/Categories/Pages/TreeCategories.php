<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Openplain\FilamentTreeView\Resources\Pages\TreePage;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use SolutionForest\FilamentTree\Actions\ViewAction as ActionsViewAction;

class TreeCategories extends TreePage
{
    protected static string $resource = CategoryResource::class;


    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTreeActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ];
    }





    // public static function tree(Tree $tree): Tree
    // {
    //     return $tree
    //         ->recordActions([
    //             // Navigate to edit page
    //             EditAction::make()
    //                 ->url(fn (Category $record): string =>
    //                     static::getUrl('edit', ['record' => $record])
    //                 ),

    //             // Edit in modal
    //             Action::make('editModal')
    //                 ->label('Quick Edit')
    //                 ->icon('heroicon-o-pencil-square')
    //                 ->fillForm(fn (Category $record): array => [
    //                     'name' => $record->name,
    //                     'description' => $record->description,
    //                 ])
    //                 ->form([
    //                     TextInput::make('name')->required(),
    //                     Textarea::make('description'),
    //                 ])
    //                 ->action(function (Category $record, array $data) {
    //                     $record->update($data);

    //                     Notification::make()
    //                         ->title('Category updated')
    //                         ->success()
    //                         ->send();
    //                 }),

    //             // Delete with descendant warning
    //             DeleteAction::make()
    //                 ->modalDescription(function (Category $record): string {
    //                     $count = $record->descendants()->count();

    //                     if ($count === 0) {
    //                         return 'Are you sure you want to delete this category?';
    //                     }

    //                     return "This category has {$count} descendants that will also be deleted.";
    //                 }),
    //         ]);
    // }

}
