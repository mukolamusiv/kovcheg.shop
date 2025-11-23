<?php

namespace App\Http\Controllers;

use App\Models\Account;
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


    public function generateTransactionAccountPDF($account_id, $date_start, $date_finish){

          $account = Account::find($account_id);
            $data = $account->getStatementForPeriod($date_start, $date_finish);
        // Генерація PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('PDF.transactionAccount', [
          'account' => $account,
          'transactions' => $data
        ]);

        dd($data);
        return $pdf->download("transaction-{$account->id}.pdf");
    }
}
