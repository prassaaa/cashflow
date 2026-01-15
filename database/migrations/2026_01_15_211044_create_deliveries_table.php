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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->constrained()->cascadeOnDelete();
            $table->string('delivery_number')->unique();
            $table->string('shipment_method')->nullable();
            $table->string('tracking_number')->nullable();
            $table->date('delivery_date');
            $table->date('received_date')->nullable();
            $table->enum('status', ['preparing', 'shipped', 'delivered', 'cancelled'])->default('preparing');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
