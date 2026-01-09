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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('cost_price')->default(0); // собівартість
            $table->integer('total_cost')->default(0); // загальна вартість
            $table->integer('mark_up')->default(100); // націнка має округлювати до цілої гривні
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_template')->default(false);
            $table->string('status')->default('оформлено'); // оформлено, передано в виробництво, виготовлено, скасовано
            $table->integer('quantity')->default(1); // кількість одиниць виробу
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
