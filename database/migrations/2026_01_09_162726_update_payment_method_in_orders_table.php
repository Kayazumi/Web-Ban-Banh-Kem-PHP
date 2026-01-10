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
        DB::table('orders')->where('payment_method', 'vnpay')->update(['payment_method' => 'cod']);
        
        // Then update enum to use 'bank_transfer' instead of 'vnpay'
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod', 'bank_transfer') DEFAULT 'cod'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to vnpay
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod', 'vnpay') DEFAULT 'cod'");
    }
};
