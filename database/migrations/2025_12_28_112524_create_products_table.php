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
        Schema::create('products', function (Blueprint $table) {
            $table->id('ProductID'); // Match existing primary key
            $table->string('product_name', 200);
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('unit', 20)->default('cái');
            $table->enum('status', ['available', 'out_of_stock', 'discontinued'])->default('available');
            $table->string('image_url', 255)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->text('ingredients')->nullable();
            $table->string('allergens', 200)->nullable();
            $table->integer('shelf_life')->nullable();
            $table->string('storage_conditions', 255)->nullable();
            $table->text('short_intro')->nullable();
            $table->text('short_paragraph')->nullable();
            $table->text('structure')->nullable();
            $table->text('usage')->nullable();
            $table->text('bonus')->nullable();
            $table->integer('views')->default(0);
            $table->integer('sold_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('CategoryID')->on('categories');
            $table->index(['status', 'is_featured', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
