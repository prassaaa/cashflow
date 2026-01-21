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
        Schema::table('job_orders', function (Blueprint $table) {
            $table->string('pic')->nullable()->after('customer_name'); // Person In Charge
            $table->string('container_name')->nullable()->after('description'); // Container/Deskripsi barang
            $table->integer('quantity')->default(0)->after('container_name'); // Jumlah
            $table->enum('unit', ['PC', 'PP', 'SET', 'PCS'])->default('PC')->after('quantity'); // Satuan
            $table->enum('pipa_status', ['pending', 'paid'])->default('pending')->after('status'); // Status Pipa
            $table->string('carton_type')->nullable()->after('pipa_status'); // Tipe Karton (RSA, INHOUSE, dll)
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('carton_type'); // Status Pembayaran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn([
                'pic',
                'container_name',
                'quantity',
                'unit',
                'pipa_status',
                'carton_type',
                'payment_status',
            ]);
        });
    }
};
