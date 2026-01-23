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

        DB::table('employees')
            ->where('type', 'contract')
            ->update(['type' => 'borongan']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE employees MODIFY type ENUM('staff','daily','borongan') DEFAULT 'staff'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE employees ALTER COLUMN type TYPE VARCHAR(20)");
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

        DB::table('employees')
            ->where('type', 'borongan')
            ->update(['type' => 'contract']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE employees MODIFY type ENUM('staff','daily','contract') DEFAULT 'staff'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE employees ALTER COLUMN type TYPE VARCHAR(20)");
            return;
        }

        // SQLite: keep as text
    }
};
