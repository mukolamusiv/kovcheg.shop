<?php

namespace App\Models;

use App\Traits\HasScaledAttributes;
use Illuminate\Database\Eloquent\Model;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\Renderers\JpgRenderer;

class Material extends Model
{

    use HasScaledAttributes;

    protected $scaled = ['stock_quantity', 'cost_per_unit'];


    protected $fillable = [
        'name',
        'slug',
        'description',
        'barcode', // Додано поле barcode
        'category_id',
        'cost_per_unit',
        'unit',
        'stock_quantity',
        'supplier_id',
        'photo_path',
        'barcode_image',

    ];

    //public $centimeters = $this->getRawOriginal('stock_quantity');


    // public function rawData()
    // {
    //    return $this->getRawOriginal('stock_quantity');
    // }

        // Accessor: для відображення користувачу (см → м)
    // public function getStockQuantityAttribute($value)
    // {
    //     return $value / 100; // користувач бачить в метрах
    // }


    public function cost_per_all()
    {
        return $this->raw('stock_quantity') * $this->raw('cost_per_unit') / 10000;
    }

    // // Mutator: при збереженні від користувача (м → см)
    // public function setStockQuantityAttribute($value)
    // {
    //     $this->attributes['stock_quantity'] = (int) round($value * 100);
    // }


    // // Accessor: для відображення користувачу (см → м)
    // public function getCostPerUnitAttribute($value)
    // {
    //     return $value / 100; // користувач бачить в метрах
    // }

    // // Mutator: при збереженні від користувача (м → см)
    // public function setCostPerUnitAttribute($value)
    // {
    //     $this->attributes['cost_per_unit'] = (int) round($value * 100);
    // }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($material) {
            $material->barcode = self::generateBarcode();
            if (empty($material->slug)) {
               $material->slug = \Str::slug($material->name);
            }
            $material->generateBarcodeImage();
        });
    }

    //     protected static function booted()
    // {
    //     // Подія перед створенням накладної
    //     static::creating(function ($material) {
    //         $material->barcode = self::generateBarcode();
    //     });
    // }


    // Видалено метод warehouses, оскільки зв'язок не описаний у міграціях

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function requiredForProduction()
    {
        return $this->hasMany(ProductionMaterial::class, 'material_id')
            ->where('status', 'на складі');
    }

    public function getStockQuantityForProductionAttribute()
    {
        return $this->requiredForProduction()->sum('quantity')/100;
    }


    public static function generateBarcode()
    {
        $prefix = '482';     // Код країни (Україна)
        $factory = '7201';   // Код виробника

        // Залишилося згенерувати 12 - (3 + 4) = 5 цифр
        $random = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);

        // Перші 12 цифр
        $base = $prefix . $factory . $random;

        // Контрольна цифра по EAN13
        $checksum = self::calculateEan13Checksum($base);

        $barcode = $base . $checksum;

        // Перевірка на унікальність
        if (self::where('barcode', $barcode)->exists()) {
            return self::generateBarcode();
        }

        return $barcode;
    }


    protected static function calculateEan13Checksum($number)
    {
        $digits = str_split($number);
        $sum = 0;

        // Проходимо по перших 12 цифрах
        foreach ($digits as $i => $digit) {
            $digit = intval($digit);

            // Парні індекси множаться на 1, непарні на 3 (по EAN правилам)
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }

        return (10 - ($sum % 10)) % 10;
    }


    public function generateBarcodeImage()
    {
        if (! $this->barcode) {
            return null;
        }

        // $generator = new BarcodeGeneratorPNG();
        // $image = $generator->getBarcode($this->barcode, $generator::TYPE_EAN_13, 3, 100);

        $generator = new BarcodeGeneratorSVG();
        $image = $generator->getBarcode($this->barcode, $generator::TYPE_EAN_13, 3, 100);

        $path = "barcodes/{$this->barcode}.svg";

        Storage::disk('public')->put($path, $image);
        $this->barcode_image = $path;

        //$this->update(['barcode_image' => $path]);

        return $path;
    }

    /**
     * Виконує переміщення матеріалів на складі, збільшуючи або зменшуючи їх кількість.
     *
     * @param int $count Кількість матеріалів для переміщення.
     * @param bool $replenishment Вказує, чи є переміщення поповненням запасів (true)
     *                            або зменшенням запасів (false).
     *
     * @return void
     */
    public function displacements(int $count, bool $replenishment)
    {
        if ($replenishment){
            $this->stock_quantity += $count;
        } else {
            $this->stock_quantity -= $count;
        }

        $this->save();
    }
}
