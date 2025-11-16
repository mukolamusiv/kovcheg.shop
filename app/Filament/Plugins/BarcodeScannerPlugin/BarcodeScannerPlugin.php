<?php

namespace App\Filament\Plugins\BarcodeScannerPlugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;

class BarcodeScannerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'barcode-scanner-plugin';
    }

    public function register(Panel $panel): void
    {
        // Підключаємо JS як asset
        $panel->assets([
            asset('/vendor/barcode-scanner/barcode-scanner.js'),
        ]);
    }

    public function boot(Panel $panel): void
    {
        // Реєстрація Blade компоненту
        // Blade::component(
        //     'barcode-listener',
        //     \App\Filament\Plugins\BarcodeScannerPlugin\Components\BarcodeListener::class
        // );
        Blade::componentNamespace(
            'App\\Filament\\Plugins\\BarcodeScannerPlugin\\Components',
            'barcode-scanner-plugin'
        );
    }

    public static function make(): static
    {
        return new static();
    }
}
