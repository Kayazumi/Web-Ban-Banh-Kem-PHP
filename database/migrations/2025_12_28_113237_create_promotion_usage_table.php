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
        Schema::create('promotion_usage', function (Blueprint $table) {
            $table->id('UsageID'); // Match existing primary key
            $table->unsignedBigInteger('promotion_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();

            $table->foreign('promotion_id')->references('PromotionID')->on('promotions');
            $table->foreign('user_id')->references('UserID')->on('users');
            $table->foreign('order_id')->references('OrderID')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_usage');
    }
};
