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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('ReviewID'); // Match existing primary key
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->tinyInteger('rating')->check('rating >= 1 AND rating <= 5');
            $table->string('title', 200)->nullable();
            $table->text('content')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('ProductID')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('UserID')->on('users');
            $table->foreign('order_id')->references('OrderID')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
