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
        Schema::create('hrd_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('status', ['staff', 'daily', 'borongan'])->default('staff');
            $table->unsignedInteger('present_count')->default(0);
            $table->unsignedInteger('absent_count')->default(0);
            $table->unsignedInteger('deduction_count')->default(0);
            $table->unsignedInteger('new_hires_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrd_attendances');
    }
};
