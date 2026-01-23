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
            DB::statement("ALTER TABLE other_costs MODIFY status ENUM('pending','approved','paid','rejected') DEFAULT 'pending'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE other_costs ALTER COLUMN status TYPE VARCHAR(20)");
            return;
        }

        // SQLite: keep as text
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE other_costs MODIFY status ENUM('pending','approved','paid') DEFAULT 'pending'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE other_costs ALTER COLUMN status TYPE VARCHAR(20)");
            return;
        }

        // SQLite: keep as text
    }
};
