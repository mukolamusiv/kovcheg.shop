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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->integer('cost_per_unit')->default(0); // вартість за одиницю
            $table->string('unit')->default('одиниці'); // одиниці вимірювання
            $table->integer('stock_quantity')->default(0); // залишок на складі
            //$table->foreignId('supplier_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('photo_path')->nullable(); // фото
            $table->json('photos')->nullable(); // галерея
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
