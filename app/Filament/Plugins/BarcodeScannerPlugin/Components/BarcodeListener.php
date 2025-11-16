<?php

namespace App\Filament\Plugins\BarcodeScannerPlugin\Components;

use Illuminate\View\Component;

class BarcodeListener extends Component
{
    public function render()
    {
        return view('barcode-scanner-plugin::components.barcode-listener');
    }
}
