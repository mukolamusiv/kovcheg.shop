<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ManagerOrdersStatsWidget extends StatsOverviewWidget implements HasActions
{
    use InteractsWithActions;

    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.manager-orders-stats-widget';

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Статистика замовлень менеджера';

    protected int | array | null $columns = 4;

    public ?int $userId = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function mount(): void
    {
        $this->dateFrom ??= now()->startOfMonth()->toDateString();
        $this->dateTo ??= now()->endOfMonth()->toDateString();
    }

    public function updatedDateFrom(): void
    {
        $this->cachedStats = null;
    }

    public function updatedDateTo(): void
    {
        $this->cachedStats = null;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading($this->getHeading())
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('dateFrom')
                                    ->label('Період від')
                                    ->live()
                                    ->required(),
                                DatePicker::make('dateTo')
                                    ->label('Період до')
                                    ->live()
                                    ->required(),
                            ]),
                    ]),
                Section::make('Підсумки')
                    ->headerActions([
                        Action::make('changeCommissionPercent')
                            ->label('Змінити відсоток')
                            ->icon('heroicon-o-pencil-square')
                            ->color('gray')
                            ->visible(fn (): bool => auth()->user()?->role === 'admin')
                            ->form([
                                TextInput::make('commission_percent')
                                    ->label('Відсоток від суми замовлень')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->suffix('%')
                                    ->required(),
                            ])
                            ->fillForm(function (): array {
                                $user = User::find($this->userId);

                                return [
                                    'commission_percent' => $user?->commission_percent ?? 1,
                                ];
                            })
                            ->action(function (array $data): void {
                                $user = User::find($this->userId);

                                if (! $user) {
                                    return;
                                }

                                $user->update([
                                    'commission_percent' => $data['commission_percent'],
                                ]);

                                $this->cachedStats = null;

                                Notification::make()
                                    ->title('Відсоток зарплати оновлено')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->schema($this->getCachedStats())
                    ->columns($this->getColumns())
                    ->contained(false)
                    ->gridContainer(),
            ]);
    }

    protected function getStats(): array
    {
        if (! $this->userId || ! $this->dateFrom || ! $this->dateTo) {
            return [];
        }

        $user = User::find($this->userId);

        if (! $user) {
            return [];
        }

        $stats = $user->getManagerOrderStats($this->dateFrom, $this->dateTo);
        $commissionPercent = (float) ($user->commission_percent ?? 1);

        $periodLabel = Carbon::parse($this->dateFrom)->format('d.m.Y')
            . ' — '
            . Carbon::parse($this->dateTo)->format('d.m.Y');

        $managerSalary = $stats['total_amount'] * ($commissionPercent / 100);

        return [
            Stat::make('Додано замовлень', (string) $stats['added_count'])
                ->description('За період: ' . $periodLabel)
                ->icon('heroicon-o-plus-circle'),
            Stat::make('Завершено замовлень', (string) $stats['completed_count'])
                ->description('Статус «Готове»')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Загальна сума', number_format($stats['total_amount'], 2, '.', ' ') . ' ₴')
                ->description('Сума доданих замовлень')
                ->color('warning')
                ->icon('heroicon-o-banknotes'),
            Stat::make('Зарплата менеджера', number_format($managerSalary, 2, '.', ' ') . ' ₴')
                ->description(number_format($commissionPercent, 2, '.', '') . '% від суми замовлень')
                ->color('info')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
