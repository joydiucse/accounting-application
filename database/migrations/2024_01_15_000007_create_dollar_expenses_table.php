<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dollar_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('category');
            $table->decimal('amount', 15, 2); // Amount in USD
            $table->decimal('exchange_rate', 8, 4)->default(1.0000); // USD to BDT exchange rate
            $table->decimal('bdt_amount', 15, 2)->nullable(); // Calculated BDT amount
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['date', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dollar_expenses');
    }
};