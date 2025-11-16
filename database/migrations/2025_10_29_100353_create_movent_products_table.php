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
        Schema::create('movent_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // продукт
            $table->foreignId('production_id')->nullable()->constrained('productions')->onDelete('cascade');
            $table->enum('destination', ['на склад', 'замовнику'])->default('замовнику')->nullable();
            // $table->foreignId('from_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            // $table->foreignId('to_warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('status')->default('на виробництві');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movent_products');
    }
};
