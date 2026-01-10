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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('ContactID'); // Match existing primary key
            
            // ✅ customer_id BẮT BUỘC (chỉ user đã đăng nhập mới liên hệ được)
            $table->unsignedBigInteger('customer_id');
            
            // ✅ THÊM 3 CỘT LƯU SNAPSHOT từ form
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('phone', 20)->nullable();
            
            $table->string('subject', 255);
            $table->text('message');
            $table->enum('status', ['pending', 'responded'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('responded_by')->references('UserID')->on('users')->onDelete('set null');
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};