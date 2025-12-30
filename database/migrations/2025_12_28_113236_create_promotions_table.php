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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('PromotionID'); // Match existing primary key
            $table->string('promotion_code', 50)->unique();
            $table->string('promotion_name', 200);
            $table->text('description')->nullable();
            $table->enum('promotion_type', ['percent', 'fixed_amount', 'free_shipping', 'gift']);
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('min_order_value', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('quantity')->default(-1); // -1 for unlimited
            $table->integer('used_count')->default(0);
            $table->integer('usage_limit_per_user')->default(1);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->string('image_url', 255)->nullable();
            $table->json('applicable_products')->nullable();
            $table->json('applicable_categories')->nullable();
            $table->enum('customer_type', ['all', 'new', 'loyal'])->default('all');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('UserID')->on('users');
            $table->index(['promotion_code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
