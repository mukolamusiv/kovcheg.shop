<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('name'); // назва
            $table->string('slug')->unique(); // url
            $table->text('description')->nullable(); // опис
            $table->string('barcode')->unique()->nullable(); // Додано поле barcode
            $table->string('barcode_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade'); // приналежність до категорії
            $table->integer('cost_per_unit')->default(0); // варітість за одиницю
            $table->string('unit')->default('метри погонні'); // одиниця вимірювання
            $table->integer('stock_quantity')->default(0); // кількість на складі
            $table->foreignId('supplier_id')->nullable()->constrained('users')->onDelete('set null'); // постачальник
            $table->string('photo_path')->nullable(); // фото
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
