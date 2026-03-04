<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;

use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            // ExportBulkAction::make('export')
            //     ->label('Export to Excel'),
            // ExportBulkAction::make('exportCsv')
            //     ->label('Export to CSV')
            //     ->fileName('orders.csv')
            //     ->withWriterType(\PhpOffice\PhpSpreadsheet\Writer\Csv::class),
        ];
    }

        public function getTabs(): array
    {
        return [
            'Всі' => Tab::make(),
            'Оформлені' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'оформлено')),
            'Виготовлено' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'виготовлено')),
            'Готові' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'готове')),
            'Скасовані' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'скасовано')),
            // 'Шаблони' => Tab::make()
            //     ->modifyQueryUsing(fn (Builder $query) => $query->where('is_template', true)),
        ];
    }
}
