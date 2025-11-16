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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_amount'); // in cents to avoid floating point issues загальна вартість
            $table->integer('discount_amount')->default(0); // in cents знижка
            $table->integer('paid_amount')->default(0); // in cents оплачено
            $table->integer('due_amount')->default(0); // in cents залишилось оплатити
            $table->date('deadline')->nullable();
            $table->enum('shipping_method', ['pickup', 'nova_poshta', 'courier', 'ukrposhta', 'door_delivery'])->default('pickup'); // метод доставки
            $table->text('notes')->nullable(); // нотатки до замовлення
            $table->integer('delivery')->default(0); // consider using enum or string for better readability вартість доставки
            $table->string('status')->default('pending'); // статус замовлення
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
