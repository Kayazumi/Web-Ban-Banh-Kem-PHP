<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing 'vnpay' values to 'bank_transfer'
        DB::table('orders')->where('payment_method', 'vnpay')->update(['payment_method' => 'bank_transfer']);

        // Then update enum to use 'bank_transfer' instead of 'vnpay'.
        // SQLite does not support MODIFY; only run ALTER ... MODIFY on MySQL.
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'pgsql') {
            // MySQL (and some other drivers) support modifying the column directly.
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod', 'bank_transfer') DEFAULT 'cod'");
        } else {
            // SQLite: cannot modify column definitions easily. Skip schema change here.
            // If stricter enforcement is required for sqlite, consider using a table-recreate approach.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to vnpay (only attempt on drivers that support MODIFY)
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'pgsql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod', 'vnpay') DEFAULT 'cod'");
        } else {
            // SQLite: nothing to revert at schema level.
        }
    }
};
