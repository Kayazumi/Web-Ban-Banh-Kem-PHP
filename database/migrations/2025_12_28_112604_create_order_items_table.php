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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('OrderItemID'); // Match existing primary key
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name', 200)->nullable();
            $table->decimal('product_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('OrderID')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('ProductID')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
