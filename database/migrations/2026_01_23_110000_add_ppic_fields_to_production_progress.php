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
        Schema::table('production_progress', function (Blueprint $table) {
            $table->string('material')->nullable()->after('stage');
            $table->string('packing')->nullable()->after('material');
            $table->text('solution')->nullable()->after('issues');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_progress', function (Blueprint $table) {
            $table->dropColumn(['material', 'packing', 'solution']);
        });
    }
};
