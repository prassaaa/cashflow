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
        Schema::table('man_powers', function (Blueprint $table) {
            $table->enum('payment_type', ['hourly', 'borongan'])->default('hourly')->after('work_date');
            $table->decimal('quantity', 10, 2)->default(0)->after('hours_worked');
            $table->decimal('rate_per_unit', 12, 2)->default(0)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('man_powers', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'quantity', 'rate_per_unit']);
        });
    }
};
