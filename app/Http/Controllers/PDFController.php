<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generateMaterialPDF($materialId)
    {
        $material = \App\Models\Material::findOrFail($materialId);

        // Переконуємось, що баркод-зображення існує, інакше створюємо
        if (empty($material->barcode_image)) {
            $material->generateBarcodeImage();
            $material->save();
        }

        // Отримання абсолютного шляху до SVG-файлу (WKHTMLTOPDF вимагає абсолютні шляхи!)
        $barcodePath = public_path('storage/' . $material->barcode_image);

        if (! file_exists($barcodePath)) {
            // Генеруємо ще раз, якщо зображення відсутнє
            $material->generateBarcodeImage();
            $material->save();
            $barcodePath = public_path('storage/' . $material->barcode_image);
        }

        // Генерація PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('PDF.material', [
            'material' => $material,
            'barcodePath' => $barcodePath
        ]);

        return $pdf->download("material-{$material->id}.pdf");
    }
}
