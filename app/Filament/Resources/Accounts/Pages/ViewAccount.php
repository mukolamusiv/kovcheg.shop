<?php

namespace App\Filament\Resources\Accounts\Pages;

use App\Filament\Resources\Accounts\AccountResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ViewRecord;


class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('generateStatement')
                ->label('Сформувати виписку')
                ->modalHeading('Виберіть період')
                ->form([
                    DatePicker::make('start_date')
                        ->label('Початкова дата')
                        ->default(now()->startOfMonth())
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Кінцева дата')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Your logic to generate the statement
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];

                     $account = $this->record;
                    // $statement = $account->getStatementForPeriod($startDate, $endDate);
                    return route('pdf.transactions', [
                        'account_id' => $account->id,
                        'date_start' => $startDate,
                        'date_finish' => $endDate,
                    ]);
                   // fn () => route('pdf.material', ['id' => $this->record->id])

                    // Example: Return the statement as a downloadable file
                    // You can customize this part to format and export the statement as needed
                  //  return response()->json($statement);
                    // Example: Generate and download the statement
                    // StatementGenerator::generate($startDate, $endDate);
                }),
        ];
    }
}
