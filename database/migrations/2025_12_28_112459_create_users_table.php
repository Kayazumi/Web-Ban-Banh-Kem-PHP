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
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserID'); // Match existing primary key
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password_hash', 255);
            $table->string('full_name', 100);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['customer', 'staff', 'admin'])->default('customer');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->string('avatar', 255)->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
