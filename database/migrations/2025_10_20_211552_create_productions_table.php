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
            $table->decimal('cost_price', 10, 2)->default(0); // собівартість
            $table->decimal('total_cost', 10, 2)->default(0); // загальна вартість
            $table->decimal('mark_up', 10, 2)->default(100); // націнка має округлювати до цілої гривні
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
