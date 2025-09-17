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
            $table->boolean('from_dollar')->default(false)->after('amount');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->boolean('from_dollar')->default(false)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('from_dollar');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('from_dollar');
        });
    }
};
