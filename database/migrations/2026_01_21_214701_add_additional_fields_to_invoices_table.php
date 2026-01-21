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
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('shipped_date')->nullable()->after('invoice_date');
            $table->string('shipper')->nullable()->after('shipped_date');
            $table->string('buyer')->nullable()->after('shipper');
            $table->string('po_number')->nullable()->after('buyer');
            $table->string('container')->nullable()->after('po_number');
            $table->decimal('deposit_discount', 15, 2)->default(0)->after('amount');
            $table->decimal('paid_amount', 15, 2)->default(0)->after('deposit_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'shipped_date',
                'shipper',
                'buyer',
                'po_number',
                'container',
                'deposit_discount',
                'paid_amount',
            ]);
        });
    }
};
