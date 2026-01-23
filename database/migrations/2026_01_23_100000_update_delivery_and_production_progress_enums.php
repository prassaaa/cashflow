<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('preparing','shipped','in_transit','delivered','returned','cancelled') DEFAULT 'preparing'");
            DB::statement("ALTER TABLE production_progress MODIFY stage ENUM('planning','material_prep','production','quality_check','finishing','packing','completed') DEFAULT 'planning'");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE deliveries ALTER COLUMN status TYPE VARCHAR(20)");
            DB::statement("ALTER TABLE production_progress ALTER COLUMN stage TYPE VARCHAR(20)");

            return;
        }

        if ($driver === 'sqlite') {
            // SQLite doesn't enforce enums; keep as text.
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('preparing','shipped','delivered','cancelled') DEFAULT 'preparing'");
            DB::statement("ALTER TABLE production_progress MODIFY stage ENUM('planning','material_prep','production','quality_check','finishing','completed') DEFAULT 'planning'");

            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE deliveries ALTER COLUMN status TYPE VARCHAR(20)");
            DB::statement("ALTER TABLE production_progress ALTER COLUMN stage TYPE VARCHAR(20)");

            return;
        }

        if ($driver === 'sqlite') {
            return;
        }
    }
};
