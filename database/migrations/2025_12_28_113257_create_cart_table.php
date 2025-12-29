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
        Schema::create('cart', function (Blueprint $table) {
            $table->id('CartID'); // Match existing primary key
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->string('note', 255)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
            $table->foreign('user_id')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('ProductID')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
