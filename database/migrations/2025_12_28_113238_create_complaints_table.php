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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('ComplaintID'); // Match existing primary key
            $table->string('complaint_code', 20)->unique();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('complaint_type', ['product_quality', 'delivery', 'service', 'other']);
            $table->string('title', 200);
            $table->text('content');
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'processing', 'resolved', 'closed', 'rejected'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->text('resolution')->nullable();
            $table->enum('compensation_type', ['refund', 'replacement', 'voucher', 'none'])->nullable();
            $table->decimal('compensation_value', 10, 2)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('OrderID')->on('orders');
            $table->foreign('customer_id')->references('UserID')->on('users');
            $table->foreign('assigned_to')->references('UserID')->on('users');
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
