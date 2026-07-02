<x-filament-widgets::widget
    :attributes="
        (new \Illuminate\View\ComponentAttributeBag)
            ->merge([
                'wire:poll.' . $this->getPollingInterval() => $this->getPollingInterval() ? true : null,
            ], escape: false)
            ->class([
                'fi-wi-stats-overview',
            ])
    "
>
    {{ $this->content }}

    <x-filament-actions::modals />
</x-filament-widgets::widget>
