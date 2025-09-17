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
        Schema::table('incomes', function (Blueprint $table) {
            $table->decimal('usd_amount', 15, 2)->nullable()->after('from_dollar');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('usd_amount');
            $table->decimal('bdt_amount', 15, 2)->nullable()->after('exchange_rate');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('usd_amount', 15, 2)->nullable()->after('from_dollar');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('usd_amount');
            $table->decimal('bdt_amount', 15, 2)->nullable()->after('exchange_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn(['usd_amount', 'exchange_rate', 'bdt_amount']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['usd_amount', 'exchange_rate', 'bdt_amount']);
        });
    }
};
