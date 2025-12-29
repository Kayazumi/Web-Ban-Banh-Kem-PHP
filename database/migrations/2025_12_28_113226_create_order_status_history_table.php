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
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id('HistoryID'); // Match existing primary key
            $table->unsignedBigInteger('order_id');
            $table->string('old_status', 20)->nullable();
            $table->string('new_status', 20);
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('OrderID')->on('orders')->onDelete('cascade');
            $table->foreign('changed_by')->references('UserID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
