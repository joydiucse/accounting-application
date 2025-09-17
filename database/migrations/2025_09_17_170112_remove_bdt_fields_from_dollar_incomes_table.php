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
        Schema::table('dollar_incomes', function (Blueprint $table) {
            $table->dropColumn(['exchange_rate', 'bdt_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dollar_incomes', function (Blueprint $table) {
            $table->decimal('exchange_rate', 10, 2)->after('amount');
            $table->decimal('bdt_amount', 15, 2)->after('exchange_rate');
        });
    }
};
