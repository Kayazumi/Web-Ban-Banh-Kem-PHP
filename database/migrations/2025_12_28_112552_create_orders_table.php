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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID'); // Match existing primary key
            $table->string('order_code', 20)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 15)->nullable();
            $table->string('customer_email', 100)->nullable();
            $table->text('shipping_address');
            $table->string('ward', 50)->nullable();
            $table->string('district', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('final_amount', 12, 2);
            $table->enum('payment_method', ['cod', 'vnpay'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('order_status', ['pending', 'order_received', 'preparing', 'delivering', 'delivery_successful', 'delivery_failed'])->default('pending');
            $table->text('note')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_time', 20)->nullable();
            $table->string('promotion_code', 50)->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('UserID')->on('users');
            $table->foreign('staff_id')->references('UserID')->on('users');
            $table->index(['customer_id', 'order_status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
