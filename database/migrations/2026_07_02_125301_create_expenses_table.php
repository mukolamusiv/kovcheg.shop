<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->decimal('amount', 10, 2)->default(0);
            $table->date('expense_date');
            $table->string('description');
            $table->string('notes')->nullable();
            $table->enum('status', ['заплановано', 'проведено', 'скасовано'])->default('заплановано');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
