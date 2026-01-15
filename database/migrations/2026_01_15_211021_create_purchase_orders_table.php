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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('job_order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('supplier_name');
            $table->enum('category', ['jo_related', 'asset', 'machine', 'facility', 'other'])->default('jo_related');
            $table->text('description')->nullable();
            $table->decimal('value', 15, 2)->default(0);
            $table->date('po_date');
            $table->date('expected_delivery_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
