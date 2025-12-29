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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('ImageID'); // Match existing primary key
            $table->unsignedBigInteger('product_id');
            $table->string('image_url', 255);
            $table->string('alt_text', 200)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('ProductID')->on('products')->onDelete('cascade');
            $table->index(['product_id', 'is_primary']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
