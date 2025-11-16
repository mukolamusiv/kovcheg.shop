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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_type')->default('готівка');
            $table->integer('ipn')->nullable();
            $table->string('currency', 3)->default('UAN');
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('address')->nullable();
            $table->string('iban')->unique()->nullable();
            $table->string('account_number')->unique()->nullable();
            $table->integer('balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
